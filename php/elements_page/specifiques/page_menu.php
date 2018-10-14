<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : Definition de la structure d'une page avec menu de navigation
  // copyright (c) 2018 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <nom_-fichier.php'
  // dependances :
  // teste avec : PHP 5.5.3 sur Mac OS 10.11 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 17-jun-2018 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // -
  // a faire :
  // -
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/page.php';
  
  require_once 'php/elements_page/specifiques/menu_application.php';
  
  // --------------------------------------------------------------------------
  class Page_Menu extends Page_Simple {
    
    protected function inclure_meta_donnees_open_graph() {
    }
    
    public function definir_elements() {
      $element = new Menu_Application($this);
      $this->ajoute_element_haut($element);
    }
  }
  
  // ==========================================================================
?>
