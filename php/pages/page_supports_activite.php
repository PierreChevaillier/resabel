<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : Definition de la classe Page_Supports_Activite
  //               Contenu de la page pour la gestion des informations
  //               sur les supports d'activite du club
  // copyright (c) 2018-2020 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin-fichier.php'
  // dependances : bootstrap
  // teste avec : PHP 7.1 sur Mac OS 10.14 ; PHP 7.3 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 28-aug-2020 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // - version initiale
  // attention :
  // a faire :
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/entete_contenu_page.php';
  require_once 'php/elements_page/specifiques/page_menu.php';
  
  require_once 'php/bdd/enregistrement_support_activite.php';
  require_once 'php/elements_page/specifiques/table_supports_activite.php';
  require_once 'php/elements_page/specifiques/vue_support_activite.php';

  // --------------------------------------------------------------------------
  class Page_Supports_Activite extends Page_Menu {
    
    public $criteres_selection = "";
    private $table = null;
    
    public function definir_elements() {
      
      parent::definir_elements();
      
      $element = new Entete_Contenu_Page();
      $element->def_titre("Supports d'activité");
      $this->ajoute_element_haut($element);

      /*
      $formulaire_selection = new Formulaire_Selection_Supports($this);
      $formulaire_selection->def_id("form_sel_sup_act");
      $formulaire_selection->def_titre("Critères de sélection");
      $this->ajoute_contenu($formulaire_selection);
      */
      if (isset($_SESSION['adm'])) {
        // Possibilite de creer de nouveaux supports
        $e = new Element_Code();
        $code_html = '<div>';
        $code_html = $code_html . '<a href="support_activite.php?a=c&typ=bat" class="btn btn-outline-primary btn-lg" role="button">Ajout d\'un nouveau bateau</a>';
        $code_html = $code_html . '<a href="support_activite.php?a=c&typ=erg" class="btn btn-outline-primary btn-lg" role="button">Ajout d\'un nouvel équipement de salle</a>';
        $code_html = $code_html . '</div>' . PHP_EOL;
        $e->def_code($code_html);
        $this->ajoute_contenu($e);
      }
      
      $this->table = new Table_Supports_Activite($this);
      $this->table->def_menu_action(new Menu_Actions_Support_Activite($this));
      $this->ajoute_contenu($this->table);
  
    }
 
    public function initialiser() {
      $supports = null;
      $ordre_tri = "code_type_support, support.numero";
      
      if (!isset($_SESSION['adm']))
        $this->criteres_selection = "support.actif = 1";
      
      Enregistrement_Support_Activite::collecter($this->criteres_selection, $ordre_tri, $supports);
      $this->table->def_elements($supports);
      parent::initialiser();
    }
    
   }
  // ==========================================================================
?>
