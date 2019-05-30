<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : Definition de la classe Page_Composantes
  //               Informations sur les composantes du club (bureau, commissions...)
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin-fichier.php'
  // dependances :
  // teste avec : PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 23-mai-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/metier/club.php';
  
  require_once 'php/elements_page/specifiques/page_menu.php';
  require_once 'php/elements_page/generiques/entete_contenu_page.php';
  require_once 'php/elements_page/generiques/entete_section.php';
  
  require_once 'php/metier/struct_orga.php';
  require_once 'php/bdd/enregistrement_struct_orga.php';
  require_once 'php/elements_page/specifiques/table_struct_orga.php';

  // --------------------------------------------------------------------------
  class Page_Composantes extends Page_Menu {
    
    public $club = null;
    
    public function __construct($nom_site, $nom_page, $liste_feuilles_style = null) {
      if (isset($_SESSION['clb']))
        $this->club = new Club($_SESSION['clb']);
      parent::__construct($nom_site, $nom_page, $liste_feuilles_style);
    }
    
    public function definir_elements() {
      
      parent::definir_elements();
      
      $element = new Entete_Contenu_Page();
      $element->def_titre("Composantes du club");
      $this->ajoute_element_haut($element);

      $composantes = array();
      Enregistrement_Composante::collecter($this->club->code(), $composantes);
      
      $roles_membres = array();
      Enregistrement_Entite_Organisationnelle::collecter($roles_membres);
      
      foreach ($composantes as $c) {
        $element = new Entete_Section();
        $element->def_titre($c->nom());
        $this->ajoute_contenu($element);
        $table = new Table_Entite_Organisationnelle($this->page(), $c, $roles_membres);
        $this->ajoute_contenu($table);
      }
      /*
      $table = new Table_Personnes($this);
      $this->ajoute_contenu($table);
    */
    }
   }
  // ==========================================================================
?>
