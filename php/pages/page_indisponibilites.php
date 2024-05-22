<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : Definition de la classe Page_Indisponibilites
  //               Informations sur les indisponibilites des supports d'activite
  //               ou sur les fermetures de site d'activite
  // copyright (c) 2018-2024 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin-fichier.php'
  // dependances :
  // - bootstrap 5.x
  // utilise avec :
  // - depuis 2023 :
  //   PHP 8.2 sur macOS 13.x
  //   PHP 8.1 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 10-jun-2019 pchevaillier@gmail.com
  // revision : 29-dec-2019 pchevaillier@gmail.com impact refonte Calendrier
// revision : 30-avr-2024 pchevaillier@gmail.com + Menu actions sur indispo
// revision : 22-may-2024 pchevaillier@gmail.com + utilisation Profil_Session
  // --------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/specifiques/page_menu.php';
  require_once 'php/elements_page/generiques/entete_contenu_page.php';

  require_once 'php/bdd/enregistrement_indisponibilite.php';
  require_once 'php/metier/calendrier.php';
require_once 'php/metier/profil_session.php';

  require_once 'php/elements_page/specifiques/table_indisponibilites.php';
require_once 'php/elements_page/specifiques/vue_indisponibilite.php';

  // --------------------------------------------------------------------------
  class Page_Indisponibilites extends Page_Menu {
    public $code_type_indisponibilite;
    private $table = null;
    //private $enregistrement_indisponibilite = null;
    private $entete;
    
    public function __construct(string $nom_site, string $nom_page,
                                int $code_type_indispo,
                                $liste_feuilles_style = null,
                                ) {
      $this->code_type_indisponibilite = $code_type_indispo;
      parent::__construct($nom_site, $nom_page, $liste_feuilles_style);
    }
    
    public function definir_elements() {
      
      parent::definir_elements();
      
      $this->entete = new Entete_Contenu_Page();
      $this->ajoute_element_haut($this->entete);
    
      $profil = new Profil_Session();
      $modif_permise = $profil->est_admin() || $profil->est_permanence() || $profil->est_club();
      
      if ($modif_permise) {
        $e = new Element_Code();
        $code_html = '<div>';
        $code_html = $code_html . '<a href="indisponibilite.php?act=c&typ=' . $this->code_type_indisponibilite
          . '" class="btn btn-outline-primary btn-lg" role="button">';
        if ($this->code_type_indisponibilite == Enregistrement_Indisponibilite::CODE_TYPE_INDISPO_SUPPORT)
          $code_html = $code_html . "Ajout nouvelle indisponibilité</a>";
        elseif ($this->code_type_indisponibilite == Enregistrement_Indisponibilite::CODE_TYPE_INDISPO_SITE)
          $code_html = $code_html . "Ajout nouvelle fermeture</a>";
        else
          $code_html = $code_html . "type indispo inconnu " . $this->code_type_indisponibilite . "</a>";
        $code_html = $code_html . '</div>' . PHP_EOL;
        $e->def_code($code_html);
        $this->ajoute_contenu($e);
      }
      
      $this->table = new Table_Indisponibilites($this);
      $this->ajoute_contenu($this->table);
  
    }
 
    public function initialiser() {
      if ($this->code_type_indisponibilite == Enregistrement_Indisponibilite::CODE_TYPE_INDISPO_SUPPORT)
        $this->entete->def_titre("Indisponibilités des supports d'activité");
      elseif ($this->code_type_indisponibilite == Enregistrement_Indisponibilite::CODE_TYPE_INDISPO_SITE)
        $this->entete->def_titre("Fermetures sites d'activité");
      else
        $this->entete->def_titre("Type indispo non défini");
      
      $this->ajouter_script('js/actions_indisponibilite.js');
      
      $menu_actions = new Menu_Actions_Indisponibilite($this);
      $this->table->def_menu_action($menu_actions);
      
      // --- Recherche des indisponibilites
      $debut = Calendrier::aujourdhui();
      $critere_selection = " date_fin >= '" . $debut->date_sql() . "'";
      
      $indisponibilites = array();
      try {
        $ok = Enregistrement_Indisponibilite::collecter(NULL,
                                                        $this->code_type_indisponibilite,
                                                        $critere_selection,
                                                        " date_debut ",
                                                        $indisponibilites);
        if (!$ok) echo '<p>Pb collecter indisponibilite</p>';
      } catch (Erreur_Type_Indisponibilite $e) {
        echo "<p>Attention type d'indispo invalide</p>";
      }
      $this->table->def_elements($indisponibilites);
      
      parent::initialiser();
    }
    
   }
  // ==========================================================================
?>
