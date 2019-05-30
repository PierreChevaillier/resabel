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
  // revision : 28-dec-2018 pchevaillier@gmail.com utilisation classe Membre
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // -
  // a faire :
  // - Cas des nouveaux : initialisation et script de controle de l'identifiant
  //  ajout du script quji fait quelque choe comme
  //  $('#formId').change(function(){...});
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/element.php';
  require_once 'php/elements_page/generiques/entete_contenu_page.php';
  require_once 'php/elements_page/specifiques/page_menu.php';
  require_once 'php/elements_page/specifiques/formulaire_membre.php';

  require_once 'php/metier/membre.php';
  
  // --------------------------------------------------------------------------
  // --- connection a la base de donnees
  //include 'php/bdd/base_donnees.php';
  
  require_once 'php/bdd/enregistrement_membre.php';
  
  // --------------------------------------------------------------------------
  class Page_Membre extends Page_Menu {
    
    //private $membre = null;
    private $form = null;
    
    public function definir_elements() {
      
      parent::definir_elements();
      
      // Creation & configuration des elements de la page
      // contextuelle selon le type d'action
      $creation =  (isset($_GET['a']) && $_GET['a'] == 'c');
      $creation_nouveau = $creation && (isset($_GET['o']) && $_GET['o'] == 'n');
      
      $modification =  (isset($_GET['a']) && $_GET['a'] == 'm');
      $modif_info_perso = $modification  && (isset($_GET['o']) && $_GET['o'] == 'u');
      $modif_info_membre = $modification  && (isset($_GET['o']) && $_GET['o'] == 'm');
      
     
      $element = new Entete_Contenu_Page();
      if ($creation)
        $element->def_titre("Nouveau membre");
      elseif ($modif_info_perso)
        $element->def_titre("Informations personnelles");
      else
        $element->def_titre("Informations membre");
      $this->ajoute_element_haut($element);
    
      
      // Initialisation des informations sur la personne
      $membre = null;
      
      if ($modification) {
        if (isset($_GET['mbr']))
          $code_membre = $_GET['mbr'];
        $membre = new Membre($code_membre);
        /* La personne existe deja
         * On va initialiser le formulaire avec les informations enregistrees
         * dans la base de donnees
        */
        $enregistrement = new Enregistrement_Membre();
        $enregistrement->def_membre($membre);
        $enregistrement->lire();
        
      } elseif ($creation_nouveau) {
        $membre = new Membre(0);
        $membre->initialiser_debutant();
      }
      
      // Ceation du formulaire pour la modification des informations
      $formulaire = new Formulaire_Membre($this, $membre);
      $this->ajoute_contenu($formulaire);
      $this->form = $formulaire;
    }
    
    public function initialiser() {
      parent::initialiser();

      $this->form->initialiser_champs();
      
    }
  }
  // ==========================================================================
?>
