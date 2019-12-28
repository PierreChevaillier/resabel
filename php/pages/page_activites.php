<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : Definition de la classe Page_Activites
  //               Gestion des informations sur les seances de la journee
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin-fichier.php'
  // dependances :
  // teste avec : PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 10-jun-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
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
  
  //require_once 'php/elements_page/specifiques/table_indisponibilites.php';

  // --------------------------------------------------------------------------
  class Page_Activites extends Page_Menu {
    private $activite_journaliere = null;
    
    protected function jour() { return $this->activite_journaliere->date_jour(); }
    
    private $entete;
   
    public function __construct($nom_site_web, $nom_page, $liste_feuilles_style = null) {
      $this->activite_journaliere = new Activite_Journaliere();
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
      $params['a'] = 'l';
      $date_ref = $this->activite_journaliere->date_jour();
      
      $grille = new Cadre_Controleur_Date();
      $this->ajoute_contenu($grille);
      
      $sel_jour = new Selecteur_Date();
      $sel_jour->def_page($this);
      $this->javascripts[] = "js/convert_date_timestamp.js";
      $sel_jour->def_id('sel_date');
      $sel_jour->date_ref = $date_ref;
      $sel_jour->page_cible = $url;
      $sel_jour->parametres = $params;
      $grille->ajouter_colonne($sel_jour, 'col-md-4');
      //$this->ajoute_contenu($sel_jour);

      $nav_jour = new Navigateur_Date();
      $nav_jour->date_ref = $date_ref;
      $nav_jour->page_cible = $url;
      $nav_jour->parametres = $params;
      
      $grille->ajouter_colonne($nav_jour, 'col-md-8');
      //$this->ajoute_contenu($nav_jour);
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

    protected function definir_affichage_activite_sites() {
      $onglets = new Zone_Onglets();
      $onglets->def_id('tabs_site');
      foreach ($this->activite_journaliere->activite_sites as $activite_site) {
        $onglet = new Conteneur_Elements();
        $site =  $activite_site->site;
        $onglet->def_id('tab_site_' . $site->code());
        $onglet->def_titre($site->nom());
        if (is_a($site, 'Site_Activite_Mer')) {
          $entete = new Entete_Section();
          $entete->def_titre("Sorties en mer");
          $onglet->elements[] = $entete;
          $this->definir_affichage_marees($activite_site, $onglet);
          //$this->definir_affichage_horaires($site, $onglet); // pour debug (a garder ?)
        } elseif  (is_a($site, 'Salle_Sport')){
          $entete = new entete_Section();
          $entete->def_titre("Séances entrainement à terre");
          //$this->definir_affichage_horaires($site, $onglet);
          $onglet->elements[] = $entete;
        }
        $onglets->elements[] = $onglet;
        $tableau = new Table_Seances($this, $activite_site);
        $onglet->elements[] = $tableau;
      }
      $this->ajoute_contenu($onglets);
    }

    protected function definir_affichage_marees($activite_site, $conteneur) {
      $cadre = new Conteneur_Repliable();
      $cadre->def_id('cadre_maree');
      $cadre->def_titre("Marées");
      $conteneur->ajouter_element($cadre);
      $table_marees = new Table_Marees_jour($activite_site->marees);
      $cadre->ajouter_element($table_marees);
    }
    
    protected function definir_affichage_horaires($site, $conteneur) {
      if (isset($site->regime_ouverture)) {
        $horaires = Afficheur_Regime_Ouverture::creer($this, $site->regime_ouverture);
        $conteneur->elements[] = $horaires;
      }
    }
    
    public function initialiser() {
      $this->entete->def_titre($this->jour()->date_texte());

/*
      $debut = $cal->aujourdhui();
      $debut_sql = $cal->formatter_date_heure_sql($debut);
      //$critere_selection = " code_type = " . $this->code_type_indisponibilite . " AND date_fin >= '" . $debut_sql . "'";
      
      $indisponibilites = null;
      $this->enregistrement_indisponibilite->collecter("$critere_selection", "", $indisponibilites);
      $this->table->def_elements($indisponibilites);
      */
      parent::initialiser();
    }
    
   }
  // ==========================================================================
?>
