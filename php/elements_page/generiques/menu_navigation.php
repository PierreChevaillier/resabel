<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : menu de navigation dans l'application
  // copyright (c) 2018-2023 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : bootstrap
  // teste avec : PHP 7.1 sur Mac OS 10.14 ; 
  //              PHP 7.0 sur hebergeur web (pas encore)
  // --------------------------------------------------------------------------
  // creation : 14-oct-2018 pchevaillier@gmail.com reprise de France 2018
  // revision : 16-dec-2018 pchevaillier@gmail.com sigle site web et home page
  // revision : 02-mar-2019 pchevaillier@gmail.com erreur toggle Menu
  // revision : 17-mar-2023 pchevaillier@gmail.com bootstrap v5.3
  // --------------------------------------------------------------------------
  // commentaires :
  // - pres a recevoir le contenu du module
  // attention :
  // -
  // a faire :
  // -
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/element.php';
  
  require_once 'php/metier/site_web.php';
  
  // ==========================================================================
  abstract class Menu_Navigation extends Element {
    
    public function __construct($page) {
      $this->def_page($page);
    }
    
    protected function afficher_debut() {
      $html_id = (strlen($this->id()) > 0) ? " id=\"" . $this->id() . "\" " : " ";
      echo '<nav class="navbar navbar-expand-lg"' . $html_id . 'role="navigation">';
      echo '<div class="container-fluid">';
      
      // Lien vers le site web du club
      echo '<a class="navbar-brand" href="' . htmlspecialchars(Site_Web::accede()->adresse_racine()) . '" target="_new">'
        . htmlspecialchars(Site_Web::accede()->sigle()) . '</a>';
      
      // Le bouton 'toggler'
      echo '<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu_nav" aria-controls="menu_nav" aria-expanded="false" aria-label="Toggle navigation">';
      echo '<span class="navbar-toggler-icon"></span>';
      echo '</button>';

      // la barre de menu en elle-meme
      echo '<div class="collapse navbar-collapse" id="menu_nav">';
      echo '<ul class="navbar-nav me-auto mb-2 mb-lg-0">'; // "\n" . '<ul class="navbar-nav mr-auto">';
    }
    
    protected function afficher_fin() {
      echo '</ul></div></div></nav>';
    }
    
  }
  
  // ==========================================================================
?>
