<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : menu de navigation dans l'application
  // copyright (c) 2018 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : bootstrap
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web (pas encore)
  // --------------------------------------------------------------------------
  // creation : 14-oct-2018 pchevaillier@gmail.com reprise de France 2018
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // - parametrage sigle club et home page
  // a faire :
  // -
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/element.php';
  
  // ==========================================================================
  abstract class Menu_Navigation extends Element {
    
    public function __construct($page) {
      $this->def_page($page);
    }
    
    protected function afficher_debut() {
      echo "        <nav class=\"navbar navbar-default\" role=\"navigation\">\n          <div class=\"container-fluid\">\n";
      
      // le bouton du menu sur smartphone
      echo "            <div class=\"navbar-header\">\n<button type=\"button\" class=\"navbar-toggle collapsed\" data-toggle=\"collapse\"   data-target=\"#menu_nav\" aria-expanded=\"false\">";
      echo "<span class=\"glyphicon glyphicon-menu-hamburger\" aria-hidden=\"true\"></span><span class=\"sr-only\">Menu</span></button>\n";
      /* echo "Menu</button>"; */
      echo "\n<a class=\"navbar-brand\" href=\"http://avironplougonvelin.fr\" target=\"_new\">AMP</a>\n";
      
      // texte modifiable par script
      //echo " <p class=\"navbar-text\" id=\"menuDynamiqueInfo\"></p>";
      echo "\n</div>\n";
      
      // la barre de menu
      echo "<div class=\"collapse navbar-collapse\" id=\"menu_nav\">\n";
      echo "<ul class=\"nav navbar-nav\" id=\"menu_items\">\n";
    }
    
    protected function afficher_fin() {
      echo "\n</ul>\n</div>\n</div>\n</nav>\n";
    }
    
  }
  
  // ==========================================================================
?>
