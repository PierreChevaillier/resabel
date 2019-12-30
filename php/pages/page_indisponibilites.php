<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : Definition de la classe Page_Indisponibilites
  //               Informations sur les indisponibilites des supports d'activite
  //               ou sur les fermetures de site d'activite
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin-fichier.php'
  // dependances :
  // teste avec : PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 10-jun-2019 pchevaillier@gmail.com
  // revision : 29-dec-2019 pchevaillier@gmail.com impact refonte Calendrier
  // --------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/specifiques/page_menu.php';
  require_once 'php/elements_page/generiques/entete_contenu_page.php';
  //require_once 'php/elements_page/generiques/entete_section.php';
  //require_once 'php/elements_page/generiques/modal.php';

  require_once 'php/bdd/enregistrement_indisponibilite.php';
  require_once 'php/metier/calendrier.php';
  //require_once 'php/elements_page/specifiques/vue_permanence.php';
  require_once 'php/elements_page/specifiques/table_indisponibilites.php';

  // --------------------------------------------------------------------------
  class Page_Indisponibilites extends Page_Menu {
    public $code_type_indisponibilite;
    private $table = null;
    private $enregistrement_indisponibilite = null;
    private $entete;
    
    public function definir_elements() {
      
      parent::definir_elements();
      
      $this->entete = new Entete_Contenu_Page();
      $this->ajoute_element_haut($this->entete);
    
      $this->enregistrement_indisponibilite  = new Enregistrement_Indisponibilite ();
      $this->table = new Table_Indisponibilites($this);
      $this->ajoute_contenu($this->table);
  
    }
 
    public function initialiser() {
      if ($this->code_type_indisponibilite == 1)
        $this->entete->def_titre("Indisponibilités des supports d'activité");
      else
        $this->entete->def_titre("Fermetures sites d'activité");
      
      $debut = Calendrier::aujourdhui();
      $critere_selection = " code_type = " . $this->code_type_indisponibilite . " AND date_fin >= '" . $debut->date_sql() . "'";
      $indisponibilites = null;
      $this->enregistrement_indisponibilite->collecter("$critere_selection", "", $indisponibilites);
      $this->table->def_elements($indisponibilites);
      
      parent::initialiser();
    }
    
   }
  // ==========================================================================
?>
