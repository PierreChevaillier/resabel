<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : definition du menu de navigation de l'application
  // copyright (c) 2018 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <nom_-fichier.php>
  // dependances :
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 14-oct-2018  pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // - 
  // a faire :
  // - script controle saisie : cryptage du mot de passe
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/menu_navigation.php';
  
  // ==========================================================================
  class Menu_Application extends Menu_Navigation {
    
    protected function afficher_menu_un() {
      echo '<li id="videos"><a href="tests/page_vide.php">VIde 1</a></li>';
    }
    
    protected function afficher_corps() {
      echo '<li id="index"><a href="index.php">Accueil</a></li>';
      $this->afficher_menu_un();
      
      echo '<li id="contacts"><a href="tests/page_vide.php">Contacts</a></li>';
      //echo "<li><a href=\"partenaires.php\">Partenaires</a></li>";
    }
    
  }
  // ==========================================================================
?>
