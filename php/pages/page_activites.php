<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : Definition de la classe Page_Activites
  //               Gestion des informations sur les seances de la journee
  // copyright (c) 2018-2024 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin-fichier.php'
  // dependances : $_GET[]
  // teste avec : PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 10-jun-2019 pchevaillier@gmail.com
  // revision : 08-jan-2020 pchevaillier@gmail.com affichage fermeture site (debut)
// revision : 22-may-2024 pchevaillier@gmail.com  utilisation Afficheur_Fermetures_Site
// revision: 05-jul-2024 pchevaillier@gmail.com * affichage marees
  // --------------------------------------------------------------------------
  // commentaires :
  // - operationnel
  // attention :
  // - pas complet
  // a faire :
  // - 
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/specifiques/page_menu.php';
  require_once 'php/elements_page/generiques/entete_contenu_page.php';
  require_once 'php/elements_page/generiques/entete_section.php';
  //require_once 'php/elements_page/generiques/modal.php';
  require_once 'php/elements_page/generiques/element.php';
  require_once 'php/elements_page/generiques/zone_onglets.php';
  
  require_once 'php/metier/activite.php';
  require_once 'php/metier/calendrier.php';
  
  require_once 'php/elements_page/specifiques/controleur_date_page.php';
  require_once 'php/elements_page/specifiques/vue_permanence.php';
  
  require_once 'php/elements_page/generiques/conteneur_repliable.php';
  require_once 'php/metier/maree.php';
  
  require_once 'php/elements_page/specifiques/vue_regime_ouverture.php';
  require_once 'php/elements_page/specifiques/table_seances.php';
  
require_once 'php/elements_page/specifiques/vue_indisponibilite.php';

  require_once 'php/elements_page/specifiques/formulaire_dispo_activite.php';
  
  // --------------------------------------------------------------------------
  class Page_Activites extends Page_Menu {
    private $activite_journaliere = null;
    
    private $contexte_action = null;
    public function contexte_action() { return $this->contexte_action; }
    public function def_contexte_action($contexte) {
      $this->contexte_action = $contexte;
    }
    
    protected function jour() { return $this->activite_journaliere->date_jour(); }
    
    private $entete;
    
    public $afficheur_action = null; // ajout 23-fev-2020: afficheur modal / resultat actions
   
    public function __construct($nom_site_web, $nom_page, $liste_feuilles_style = null) {
     
      $this->activite_journaliere = new Activite_Journaliere();
      
      $jour = isset($_GET['j']) ? new Instant($_GET['j']): Calendrier::aujourdhui();
      $this->activite_journaliere->def_date_jour($jour);
      
      $this->activite_journaliere->filtre_site = (isset($_GET['sa'])) ? $_GET['sa'] : 0;
      $this->activite_journaliere->filtre_type_support = (isset($_GET['ts'])) ? $_GET['ts'] : 0;
      $this->activite_journaliere->filtre_support = (isset($_GET['s'])) ? $_GET['s'] : 0;
      
      $premier_creneau = (isset($_GET['pc'])) ? new DateInterval($_GET['pc']) : new DateInterval('PT0H');
      $debut_plage_horaire = $jour->add($premier_creneau);
      $this->activite_journaliere->debut_plage_horaire = $debut_plage_horaire;
      
      $dernier_creneau = (isset($_GET['dc'])) ? new DateInterval($_GET['dc']) : new DateInterval('PT23H');
      $fin_plage_horaire = $jour->add($dernier_creneau);
      if ($fin_plage_horaire < $debut_plage_horaire)
        $fin_plage_horaire = $debut_plage_horaire;
      $this->activite_journaliere->fin_plage_horaire = $fin_plage_horaire;
      
      $this->activite_journaliere->collecter_informations();
      parent::__construct($nom_site_web, $nom_page, $liste_feuilles_style);
    }
    
    public function definir_elements() {
      parent::definir_elements();
          
      $this->entete = new Entete_Section(); //Contenu_Page();
      $this->ajoute_element_haut($this->entete);
      
      $this->definir_affichage_navigateur_dates();
      $this->definir_affichage_permanence();
     
      $this->definir_affichage_activite_sites();
      
    }
 
    
    protected function definir_affichage_navigateur_dates() {
      $url = "activites.php";
      $params = array();
      foreach ($_GET as $cle => $valeur)
        if ($cle != 'j') // traite dans tous les cas
          $params[$cle] = $valeur;
      /*
      if (is_null($this->contexte_action))
        $params['a'] = 'l';
      else
        $params['a'] = $this->contexte_action->action(); // 'l';
       */
      $date_ref = $this->activite_journaliere->date_jour();
      
      $nav_date = new Menu_Navigation_date(); //Cadre_Controleur_Date();
      $this->ajoute_contenu($nav_date);
      $nav_date->date_ref = $date_ref;
      $nav_date->page_cible = $url;
      $nav_date->parametres = $params;
      $nav_date->def_id('nav_dte');
      $nav_date->def_titre("Date");
      
    }
    
    protected function definir_affichage_permanence() {
      if (isset($this->activite_journaliere->permanence)) {
        $cadre = new Conteneur_Repliable();
        $cadre->def_id('cadre_perm');
        $cadre->def_titre("Permanence semaine");
        $this->ajoute_contenu($cadre);
        $afficheur_permanence = new Afficheur_Responsable_Permanence($this);
        $afficheur_permanence->permanence = $this->activite_journaliere->permanence;
        $cadre->ajouter_element($afficheur_permanence);
      }
    }

    protected function definir_affichage_filtre() {
      // pas encore utilise
      $cadre = new Conteneur_Repliable();
      $cadre->def_id('cadre_filtre');
      $cadre->def_titre("Filtre sélection");
      $this->ajoute_contenu($cadre);
      $mode = 'l';
      $code_site_selectionne = 1;
      $selecteur_activites = new Formulaire_Disponibilite_Activite($this,
                                                                   $mode,
                                                                   $code_site_selectionne);
      $cadre->ajouter_element($selecteur_activites);
    }
    
    protected function definir_affichage_activite_sites() {
      $onglets = new Zone_Onglets();
      $onglets->def_id('tabs_site');
      foreach ($this->activite_journaliere->activite_sites as $activite_site) {
        $onglet = new Conteneur_Elements();
        $site = $activite_site->site;
        $onglet->def_id('tab_site_' . $site->code());
        $onglet->def_titre($site->nom());
        if (is_a($site, 'Site_Activite_Mer')) {
          $entete = new Entete_Section();
          $entete->def_titre("Sorties en mer");
          $onglet->elements[] = $entete;
          $this->definir_affichage_marees($activite_site, $onglet);
          //$this->definir_affichage_horaires($site, $onglet); // pour debug (a garder ?)
        } elseif  (is_a($site, 'Salle_Sport')){
          $entete = new Entete_Section();
          $entete->def_titre("Séances entrainement à terre");
          //$this->definir_affichage_horaires($site, $onglet);
          $onglet->elements[] = $entete;
        }
        /*
        $cadre_selection = new Conteneur_Repliable();
        $cadre_selection->def_id('cadre_filtre');
        $cadre_selection->def_titre("Filtre sélection");
        $onglet->elements[] = $cadre_selection;
        $mode = 'l';
        $selecteur_activites = new Formulaire_Disponibilite_Activite($this,
                                                                   $mode, $site->code());
        $selecteur_activites->initialiser();
        $cadre_selection->ajouter_element($selecteur_activites);
        */
        $onglets->elements[] = $onglet;
        
        $ouvert = !$activite_site->site_ferme();
        if ($ouvert) {
          $this->afficheur_action = new Element_Modal();
          $this->afficheur_action->def_id('aff_act_' . $site->code());
          $this->afficheur_action->def_titre('Action effectuée');
          $this->ajoute_contenu($this->afficheur_action);
          
          $tableau = new Table_Seances($this, $activite_site);
          $onglet->elements[] = $tableau;
        } else {
          $onglet->elements[] = new Afficheur_Fermetures_Site($this->page,
                                                  $activite_site->fermetures_site);
        }
      }
      $this->ajoute_contenu($onglets);
    }

    protected function definir_affichage_marees($activite_site, $conteneur) {
      if (count($activite_site->marees) > 0) {
        $cadre = new Conteneur_Repliable();
        $cadre->def_id('cadre_maree');
        $cadre->def_titre("Marées");
        $conteneur->ajouter_element($cadre);
        $table_marees = new Table_Marees_jour($activite_site->marees);
        $cadre->ajouter_element($table_marees);
        }
    }
    
    protected function definir_affichage_horaires($site, $conteneur) {
      if (isset($site->regime_ouverture)) {
        $horaires = Afficheur_Regime_Ouverture::creer($this, $site->regime_ouverture);
        $conteneur->elements[] = $horaires;
      }
    }
    
    public function initialiser() {
      $texte_entete = $this->jour()->date_texte();
      if (isset($_GET['a'])) {
        if ($_GET['a'] == 'l')
          $texte_entete = " Vue séances " . $texte_entete;
        elseif ($_GET['a'] == 'ii' || $_GET['a'] == 'ie')
          $texte_entete = "Inscriptions séances " . $texte_entete;
      }
      $this->entete->def_titre($texte_entete);
      parent::initialiser();
    }
    
   }
  // ==========================================================================
?>
