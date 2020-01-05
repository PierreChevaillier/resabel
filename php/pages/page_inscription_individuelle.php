<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : Definition de la classe Page_Inscription_Individuelle
  //               Saisie des criteres de recherche de disponibilite
  //               de support(s) d'activite
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <nom_fichier.php>
  // dependances :
  // teste avec : PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 10-jul-2019 pchevaillier@gmail.com
  // revision : 08-sep-2019 pchevaillier@gmail.com site selectionne
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // -
  // a faire :
  // ==========================================================================

  // --------------------------------------------------------------------------
  // --- Classes utilisees
  require_once 'php/elements_page/generiques/element.php';
  require_once 'php/elements_page/generiques/entete_contenu_page.php';
  require_once 'php/elements_page/specifiques/page_menu.php';
  
  require_once 'php/elements_page/specifiques/formulaire_dispo_activite.php';

  // --------------------------------------------------------------------------
  class Page_Inscription_Individuelle extends Page_Menu {
    
    //private $membre = null;
    private $form = null;
    
    public function definir_elements() {
      
      parent::definir_elements();
      
      // Creation & configuration des elements de la page
      // contextuelle selon le type d'action
      $indiv = (isset($_GET['a']) && $_GET['a'] == 'ii');
      $equip = (isset($_GET['a']) && $_GET['a'] == 'ie');
      
      $site_selectionne = 1; // pourrait etre dans $_GET ???
      
      $element = new Entete_Contenu_Page();
       $this->ajoute_element_haut($element);
      if ($indiv) {
        $element->def_titre("Inscription individuelle");
      } elseif($equip) {
        $element->def_titre("Inscription équipage");
      } else {
        $element->def_titre("Erreur type d'inscription...");
        return;
      }
      // Ceation du formulaire pour la modification des informations
      
      $mode =  $_GET['a'];
      $formulaire = new Formulaire_Disponibilite_Activite($this, $mode, $site_selectionne);
      $this->ajoute_contenu($formulaire);
      $this->form = $formulaire;
      
    }
    
    public function initialiser() {
      parent::initialiser();
      if (isset( $this->form)) $this->form->initialiser_champs();
    }
  }
  // ==========================================================================
?>
