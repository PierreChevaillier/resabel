<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : Definition de la classe Page_Accueil_Perso
  //               Sorte de portail / personne
  // copyright (c) 2018-2022 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin-fichier.php>
  // dependances : bootstrap 4.x, valeur variables $_SESSION
  // teste avec : PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur hebergeur web
  //  - PHP 8.2 sur macOS 13.1 (> 25-dec-2022)
  // --------------------------------------------------------------------------
  // creation : 04-mar-2020 pchevaillier@gmail.com
  // revision : 29-mar-2020 pchevaillier@gmail.com ameliorerations + marees
  // revision : 29-dec-2022 pchevaillier@gmail.com fix erreur 8.2, utf8_encode deprecated
  // --------------------------------------------------------------------------
  // commentaires :
  // - en cours d'evolution
  // attention :
  // - pas complet
  // - $code_site = 1; EN DUR (suppose etre un site pour lequel il a des marees)
  //    et etre le site de base du club
  // a faire :
  // - pas completement teste, a completer
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/specifiques/page_menu.php';
  require_once 'php/elements_page/generiques/entete_contenu_page.php';
  require_once 'php/elements_page/generiques/entete_section.php';
 
  require_once 'php/metier/calendrier.php';
  
  require_once 'php/metier/site_activite.php';
  require_once 'php/bdd/enregistrement_site_activite.php';
  
  require_once 'php/metier/personne.php';
  require_once 'php/bdd/enregistrement_membre.php';
  
  require_once 'php/metier/support_activite.php';
  require_once 'php/bdd/enregistrement_support_activite.php';
  
  require_once 'php/metier/seance_activite.php';
  require_once 'php/bdd/enregistrement_seance_activite.php';
  require_once 'php/elements_page/specifiques/vue_seance_activite.php';
  
  require_once 'php/elements_page/specifiques/vue_personne.php';

  // Affichage permanence de la semaine actuelle
  require_once 'php/metier/permanence.php';
  require_once 'php/bdd/enregistrement_permanence.php';
  require_once 'php/elements_page/specifiques/vue_permanence.php';
 
  // affichage des marees
  require_once 'php/elements_page/generiques/conteneur_repliable.php';
  require_once 'php/metier/maree.php';
  
  // --------------------------------------------------------------------------
  class Page_Accueil_Perso extends Page_Menu {
    
    private $maintenant = null;
    private function code_utilisateur() { return $this->contexte->utilisateur->code(); }
    private $contexte = null;
    
    private $sites = array();
    private $permanence = null;
    private $personnes = null;
    private $supports_activite = null;
    private $seances = array();

    public function __construct($nom_site_web, $nom_page, $liste_feuilles_style = null) {
    
      $this->maintenant = Calendrier::maintenant();
      
      $this->contexte = new Contexte_Action_Seance($this);
      $this->ajouter_script("js/afficher_infos_seance_activite.js");
      $this->ajouter_script("js/requete_inscription_individuelle.js");
      
      $this->contexte->initialiser();
      
      //$this->code_utilisateur = $_SESSION['usr'];
      
      $this->collecter_informations();
      parent::__construct($nom_site_web, $nom_page, $liste_feuilles_style);
    }

    protected function collecter_informations() {
      $this->collecter_info_sites();
      $this->collecter_info_permanence();
      $this->collecter_info_personnes();
      $this->collecter_info_supports();
      $this->collecter_info_seances_activite();
    }
    
    protected function collecter_info_sites() {
      Enregistrement_Site_Activite::collecter("", " code_type ",  $this->sites);
    }
    
    protected function collecter_info_permanence() {
      $jour = $this->maintenant->jour();
      $sem = $jour->format("W");
      $annee = Calendrier::annee_semaine($jour);
      $this->permanence = new Permanence($sem, $annee);
      $enregistrement_permanence = new Enregistrement_Permanence();
      $enregistrement_permanence->def_permanence($this->permanence);
      $enregistrement_permanence->lire();
     }
    
    protected function collecter_info_personnes() {
      $personnes = null;
      $criteres = array();
      Enregistrement_Membre::collecter($criteres, '', '', $personnes);
      $this->personnes = $personnes;
    }
    
    protected function collecter_info_supports() {
      $supports = null;
      Enregistrement_Support_Activite::collecter("", "", $supports);
      $this->supports_activite = $supports;
    }
    
    protected function collecter_info_seances_activite() {
      $seances = array();
      $jour = $this->maintenant->jour();
      $critere_selection = "date_debut >= '" . $jour->date_sql() . "'";
      $critere_tri =  " date_debut ASC, code_site ASC, code_support ASC ";
      Enregistrement_Seance_Activite::collecter(NULL,
                                               $critere_selection,
                                               $critere_tri,
                                               $seances);
      // on complette les informations sur les personnes et les supports d'activite
      foreach ($seances as $seance) {
        $seance_perso = false;
        $code_site = $seance->site->code();
        $seance->site = $this->sites[$code_site];
        
        $code_support = $seance->support->code();
        //if (key_exists($i, $this->supports_activite)) {
          $seance->support = $this->supports_activite[$code_support];
        //}
        if ($seance->a_un_responsable()) {
          $code_personne = $seance->responsable->code();
          $seance->responsable = $this->personnes[$code_personne];
          if (!$seance_perso && ($code_personne == $this->code_utilisateur()))
            $seance_perso = true;
        }
        foreach ($seance->inscriptions as $participation) {
          $code_personne = $participation->participant->code();
          $participation->participant = $this->personnes[$code_personne];
          if (!$seance_perso && ($code_personne == $this->code_utilisateur()))
             $seance_perso = true;
        }
        if ($seance_perso)
          $this->seances[] = $seance;
      }
      
    }
    
    public function definir_elements() {
      parent::definir_elements();
      
      $element = new Entete_Contenu_Page();
      $element->def_titre("Page personnelle " . $_SESSION['n_usr']);
      $this->ajoute_element_haut($element);
      
      $element = new Entete_Section();
      $maintenant = Calendrier::maintenant();
      $aujourdhui = $maintenant->jour();
      $element->def_titre($aujourdhui->date_texte());
      $this->ajoute_contenu($element);
      
      $this->definir_affichage_permanence();
      $this->definir_affichage_marees();
      
       // --- Contenu temporaire
      // --- Explications sur ce qu'il y aura sur la page
      /*
      $doc = new Element_Code();
      $code_html = '<div>' . PHP_EOL;
      $code_html = $code_html . 'Cette page donne aussi des informations sur <ul><li>date - lever - coucher soleil</li><li>les éventuelles fermetures de site</li></ul>' . PHP_EOL;
      $code_html = $code_html . '<p>Elle donne accès au formulaire d\'inscription individuelle et équipage (idem menu inscription)</p>' . PHP_EOL;
      $code_html = $code_html . '<p>Si la personne est de permanence, cela est indiqué.</p>' . PHP_EOL;
      $code_html = $code_html . '<p>Si la personne doit renouveler prochainement sa licence, cela est indiqué.</p>' . PHP_EOL;
      $code_html = $code_html . '</div>' . PHP_EOL;
      $doc->def_code($code_html);
      $this->ajoute_contenu($doc);
    */
      $element = new Entete_Section();
      $element->def_titre("Participations à des séances d'activité");
      $this->ajoute_contenu($element);
      $this->definir_affichage_seances_activite();
    }
    
    protected function definir_affichage_seances_activite() {
      $afficheur_action = new Element_Modal();
      $afficheur_action->def_id('aff_act');
      $afficheur_action->def_titre('Action effectuée');
      $this->ajoute_contenu($afficheur_action);
      
      $aff_tel = new Afficheur_Telephone();
      $aff_mail = new Afficheur_Courriel_Actif();
      
      foreach ($this->seances as $seance) {
        $cadre = new Conteneur_Repliable();
        $entete = $seance->site->nom() . ", le " . $seance->debut()->date_texte_court()
        . " de " . $seance->debut()->heure_texte() . " à " . $seance->fin()->heure_texte();
        $info_support = " sur " . $seance->support->nom();
        if (is_a($seance->support, "Bateau"))
           $info_support = $info_support . " (" . $seance->support->numero() . ")";
        $entete = $entete . $info_support;
        $cadre->def_id('seance_' . $seance->code());
        $cadre->def_titre($entete);
        
        $afficheur_seance = new Afficheur_Vertical_Seance($this, $seance, NULL);
        $afficheur_seance->est_interactif = false;
        $x = new Element_Code();
        $code_info_seance = $afficheur_seance->formater();
        
        // definir menu des actions possibles
        $code_menu = "\n\n";
        $code_menu = $code_menu . '<div class="btn-group dropup"><button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="XXXXXXXX" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Actions</button><div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
        $details = ""; //utf8_encode("");
        $sujet = "Sortie du " . $seance->debut()->date_texte()
               . " à " . $seance->debut()->heure_texte();
        $info_support = " sur " . $seance->support->nom();
        if (is_a($seance->support, "Bateau"))
          $info_support = $info_support . " (" . $seance->support->numero() . ")";
        $sujet = $sujet . " " . $info_support;
        $sujet = $sujet . " - " . $seance->site->nom();
        foreach ($seance->inscriptions as $participation) {
          $p = $participation->participant;
          $details = $details . $p->prenom() . " " . $p->nom();
          $details = $details . " " . $aff_tel->formatter($p->telephone);
          $aff_mail->def_personne($p);
          $details = $details . " " . $aff_mail->formatter("Je te contacte ", $sujet);
          $details = $details . "<br />";
        }
        $details = htmlspecialchars($details); // indispensable car il ya des " dans $details
        $modal_id = "aff_act";
        $code_menu = $code_menu . '<a class="dropdown-item" data-toggle="modal" data-target="#aff_act" onclick="return afficher_info_seance(\'aff_act\', \''
            . $entete . '\', \'' . $details . '\');">Afficher informations</a>';

        $params = '\'' . $modal_id . '\', '
                           . $seance->code() . ', '
                           . $seance->site->code() . ', '
                           . $seance->code_support() . ', '
                           . '\'' . $seance->debut()->date_heure_sql() . '\', '
                           . '\'' . $seance->fin()->date_heure_sql() . '\'';
        $resp = $seance->a_comme_responsable($this->contexte->utilisateur) ? 1: 0;
        $params = $params . ', ' . $this->contexte->utilisateur->code() . ', ' . $resp; // 0 : participation pas en tant que responsable (chef de bord)
        $code_action = "di"; // annulation inscription individuelle
        $params = $params . ', \'' . $code_action . '\'';
        $code_menu = $code_menu . '<a class="dropdown-item" data-toggle="modal" data-target="#aff_act" onclick="requete_inscription_individuelle(' . $params . ');">Annuler ma participation</a>';
        if ($seance->responsable_requis() && !$seance->a_un_responsable() && $this->contexte->utilisateur_responsable())
          $code_menu = $code_menu . '<a class="dropdown-item" onclick="return true;">Passer chef de bord</a>';
        if ($seance->a_un_responsable() && $seance->responsable->code() == $this->code_utilisateur())
          $code_menu = $code_menu . '<a class="dropdown-item" onclick="return true;">Laisser la place de chef de bord</a>';
        if ($seance->nombre_participants() > 1)
          $code_menu = $code_menu . '<a class="dropdown-item" onclick="return true;">Contacter les participants</a>';
        $code_menu = $code_menu . '</div></div>' . PHP_EOL;
        
        $x->def_code($code_info_seance . $code_menu);
        $cadre->ajouter_element($x);
        $this->ajoute_contenu($cadre);
      }
    }
    
    protected function definir_affichage_permanence() {
      if (!is_null($this->permanence)) {
        $cadre = new Conteneur_Repliable();
        $cadre->def_id('cadre_perm');
        $cadre->def_titre("Permanence semaine");
        $this->ajoute_contenu($cadre);
        $afficheur_permanence = new Afficheur_Responsable_Permanence($this);
        $afficheur_permanence->permanence = $this->permanence;
        $cadre->ajouter_element($afficheur_permanence);
      }
    }

    protected function definir_affichage_marees() {
      $cadre = new Conteneur_Repliable();
      $cadre->def_id('cadre_maree');
      $cadre->def_titre("Marées");
      
      $this->ajoute_contenu($cadre);
      $code_site = 1;
      $maintenant = Calendrier::maintenant();
      $marees = Enregistrement_Maree::recherche_marees_jour($code_site,  $maintenant->jour());
      $table_marees = new Table_Marees_jour($marees);
      $cadre->ajouter_element($table_marees);
    }
    
    
   }
  // ==========================================================================
?>
