<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : Definition de la classe Page_Personnes
  //               Informations sur les personnes du club (membres...)
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin-fichier.php'
  // dependances :
  // teste avec : PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 16-mar-2018 pchevaillier@gmail.com
  // revision : 29-mar-2018 pchevaillier@gmail.com nettoyage du code
  // --------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/entete_contenu_page.php';
  //require_once 'php/elements_page/generiques/modal.php';
  require_once 'php/elements_page/specifiques/page_menu.php';
  require_once 'php/elements_page/specifiques/formulaire_selection_personne.php';
  require_once 'php/bdd/enregistrement_membre.php';
  require_once 'php/elements_page/specifiques/table_personnes.php';
  require_once 'php/elements_page/specifiques/vue_personne.php';

  // --------------------------------------------------------------------------
  class Page_Personnes extends Page_Menu {
    
    public $criteres_selection = null;
    private $table = null;
    
    public function definir_elements() {
      
      parent::definir_elements();
      
      $element = new Entete_Contenu_Page();
      $element->def_titre("Carnet d'adresses");
      $this->ajoute_element_haut($element);
    
      $formulaire_selection = new Formulaire_Selection_Personne($this);
      $formulaire_selection->def_id("form_sel_prs");
      $formulaire_selection->def_titre("Critères de sélection");
      $this->ajoute_contenu($formulaire_selection);
      
      $this->table = new Table_Personnes($this);
      $this->table->def_menu_action(new Menu_Actions_Membre($this));
      $this->ajoute_contenu($this->table);
  
    }
 
    public function initialiser() {
      $personnes = null;
      $composante = '';
      $role = '';
      Enregistrement_Membre::collecter($this->criteres_selection, $composante, $role, $personnes);
      $this->table->def_personnes($personnes);
      parent::initialiser();
    }
    
   }
  // ==========================================================================
?>
