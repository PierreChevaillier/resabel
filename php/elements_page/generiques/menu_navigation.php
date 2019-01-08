<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : menu de navigation dans l'application
  // copyright (c) 2018 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : bootstrap 4.1
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web (pas encore)
  // --------------------------------------------------------------------------
  // creation : 14-oct-2018 pchevaillier@gmail.com reprise de France 2018
  // revision : 16-dec-2018 pchevaillier@gmail.com sigle site web et home page
  // --------------------------------------------------------------------------
  // commentaires :
  // -
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
      echo "        <nav class=\"navbar navbar-expand-lg navbar-light\" role=\"navigation\">\n";
    
      // Lien vers le site web du club
      echo "\n<a class=\"navbar-brand\" href=\"" . htmlspecialchars(Site_Web::accede()->adresse_racine()) . "\" target=\"_new\">" . htmlspecialchars(Site_Web::accede()->sigle()) . "</a>\n";
      
      // toggler
      echo ' <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">';
      echo '<span class="navbar-toggler-icon"></span>';
      echo '</button>';
      
      echo '<div class="collapse navbar-collapse" id="menu_nav">';
      //echo "\n</div>\n";
      
      // la barre de menu
      echo "\n" . '<ul class="navbar-nav mr-auto">';
    }
    
    protected function afficher_fin() {
      echo "\n</ul>\n</div>\n</nav>\n";
    }
    
  }
  
  // ==========================================================================
?>
