<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : Definition de la classe Page_Membre
  //               Informations sur un membre du club
  // copyright (c) 2018 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <nom_-fichier.php'
  // dependances :
  // teste avec : PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 06-dec-2018 pchevaillier@gmail.com
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
  require_once 'php/elements_page/generiques/entete_contenu_page.php';
  require_once 'php/elements_page/specifiques/page_menu.php';
  require_once 'php/elements_page/specifiques/formulaire_membre.php';

  require_once 'php/metier/personne.php';
  
  // --------------------------------------------------------------------------
  // --- connection a la base de donnees
  //include 'php/bdd/base_donnees.php';
  
  require_once 'php/bdd/enregistrement_personne.php';
  
  // --------------------------------------------------------------------------
  class Page_Membre extends Page_Menu {
    
    private $membre = null;
    private $form = null;
    
    public function definir_elements() {
      
      parent::definir_elements();
      
      $element = new Entete_Contenu_Page();
      $element->def_titre("Informations personnelles");
      $this->ajoute_element_haut($element);
    
      $formulaire = new Formulaire_Membre($this);
      $this->ajoute_contenu($formulaire);
      $this->form = $formulaire;
    }
    
    public function initialiser() {
      parent::initialiser();

      if (isset($_GET['usr']))
        $code_membre = $_GET['usr'];
      else
        $code_membre = $_SESSION['usr'];
      
      $membre = new Personne($code_membre);
      
      $enregistrement = new Enregistrement_Personne();
      $enregistrement->def_personne($membre);
      $enregistrement->lire();
      $this->form->initialiser_champs($membre);
      
    }
  }
  // ==========================================================================
?>
