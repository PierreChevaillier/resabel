<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classes definissant les 'vues' (= 'presentations')
  //               d'un objet de la classe Support_Activite
  // copyright (c) 2018-2020 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : bootstratp 4.x, variables de SESSION
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 29-aug-2020 pchevaillier@gmail.com
  // revision : 11-mar-2019 pchevaillier@gmail.com id_club
  // revision : 16-mar-2019 pchevaillier@gmail.com Menu_Actions_Personne
  // revision : 29-mar-2019 pchevaillier@gmail.com Menu_Actions_Membre
  // --------------------------------------------------------------------------
  // commentaires :
  // - en evolution
  // attention :
  // -
  // a faire :
  // ==========================================================================
  
  require_once 'php/metier/support_activite.php';
  require_once 'php/elements_page/generiques/element.php';
  require_once 'php/elements_page/generiques/modal.php';

  // --------------------------------------------------------------------------
  class Menu_Actions_Support_Activite extends Element {
    public $support_activite = null;
    public function def_objet($objet_metier) {
      $this->support_activite = $objet_metier;
    }
    public $afficheur_info = null;
    public $afficheur_action = null;
    
    public function __construct($page) {
      $this->def_page($page);
      // ajout des fichiers des scripts associes aux actions du menu
      $page->javascripts[] = "js/requete_info_support_activite.js";
      $page->javascripts[] = "js/requete_maj_support_actif.js";
      
      // Element modal pour affichage des informations sur l'objet
      $this->afficheur_info = new Element_Modal();
      $this->afficheur_info->def_id('aff_sup_act');
      $this->afficheur_info->def_titre('Informations Support');
      $this->page()->ajoute_contenu($this->afficheur_info);
      
      // Element modal pour indiquer le resultat d'une action
      $this->afficheur_action = new Element_Modal();
      $this->afficheur_action->def_id('aff_msg_sup_act');
      $this->afficheur_action->def_titre('Action effectuée');
      $this->page()->ajoute_contenu($this->afficheur_action);
       
    }
    
    public function initialiser() {
     
    }
    
    protected function afficher_debut() {
      echo '<div class="dropdown"><button class="btn  btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>';
      echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
    }
    
    protected function afficher_actions() {
      echo '<a class="dropdown-item" data-toggle="modal" data-target="#' . $this->afficheur_info->id() . '"  onclick="return requete_info_support_activite(' . $this->support_activite->code() .', \'' . $this->afficheur_info->id() . '\');">Afficher</a>';
      if (isset($_SESSION['adm'])) {
        $type_objet = (is_a($this->support_activite, 'Bateau')) ? 'bat' : 'erg';
        echo '<a class="dropdown-item" href="support_activite.php?a=m&typ=' . $type_objet . '&sua=' . $this->support_activite->code() . '">Modifier</a>';
        if ($this->support_activite->est_actif())
          echo '<a class="dropdown-item" data-toggle="modal" data-target="#' . $this->afficheur_action->id() . '"  onclick="return requete_maj_support_actif(' . $this->support_activite->code() . ', 0, \'' . $this->afficheur_action->id() . '\');">Rendre inactif</a>';
        else
          echo '<a class="dropdown-item" data-toggle="modal" data-target="#' . $this->afficheur_action->id() . '"  onclick="return requete_maj_support_actif(' . $this->support_activite->code() . ', 1, \'' . $this->afficheur_action->id() . '\');">Rendre actif</a>';
      }
    }
    protected function afficher_corps() {
      $this->afficher_actions();
    }
    
    protected function afficher_fin() {
      echo "</div></div>\n";
    }
    
  }
    
  // ==========================================================================
?>
