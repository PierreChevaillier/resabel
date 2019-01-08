<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : definition du menu de navigation de l'application
  // copyright (c) 2018 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <nom_-fichier.php>
  // dependances : bootstrap 4.x
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 14-oct-2018 pchevaillier@gmail.com
  // revision : 29-dec-2018 pchevaillier@gmail.com deconnexion
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // - 
  // a faire :
  // - variables pour acronyme club et lien home-page
  // - affichage nom user (? ou dans barre au dessus, comme dans France2018)
  // - ajouter logique acces aux items du menu
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/menu_navigation.php';
  
  // ==========================================================================
  class Menu_Application extends Menu_Navigation {
    
    public function initialiser() {}
    
    private function afficher_menu_inscription() {
      echo ' <li class="nav-item dropdown">';
      echo '<a class="nav-link dropdown-toggle" href="#" id="mnu_inscr" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Inscriptions</a>';
      echo ' <div class="dropdown-menu" aria-labelledby="mnu-inscr">';
      if (isset($_SESSION['prs']))
          echo '<a class="dropdown-item" href="tests/vide.php">Individuelle</a>';
      echo '<a class="dropdown-item" href="tests/vide.php">Equipage</a>';
      echo '</div></li>';
    }
    
    private function afficher_menu_administration() {
      
      echo ' <li class="nav-item dropdown">';
      echo '<a class="nav-link dropdown-toggle" href="#" id="mnu_admin" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Administration</a>';
      echo ' <div class="dropdown-menu" aria-labelledby="mnu-admin">';
      echo '<a class="dropdown-item" href="tests/vide.php">Equipe permanences</a>';
      echo '<a class="dropdown-item" href="tests/vide.php">Visiteurs</a>';
      echo '</div></li>';
    }
    
    protected function afficher_corps() {
      echo '<li class="nav-item"><a class="nav-link" href="page_temporaire.php">Accueil</a></li>';

      if (!isset($_SESSION['prs']) || (isset($_SESSION['prs']) && isset($_SESSION['act'])))
          $this->afficher_menu_inscription();

      echo '<li class="nav-item"><a class="nav-link" href="#">Sorties</a></li>';
      echo '<li class="nav-item"><a class="nav-link" href="#">Permanences</a></li>';
      echo '<li class="nav-item"><a class="nav-link" href="#">Indisponibilités</a></li>';
      echo '<li class="nav-item"><a class="nav-link" href="#">Contacts</a></li>';
      echo '<li class="nav-item"><a class="nav-link" href="#">Bateaux</a></li>';
      
      if (isset($_SESSION['adm']) && $_SESSION['adm'])
          $this->afficher_menu_administration();

      if ($_SESSION['prs'])
          echo '<li class="nav-item"><a class="nav-link" href="membre.php?mbr=' . $_SESSION['usr'] . '">' .  htmlspecialchars($_SESSION['n_usr']) . '</a></li>';

      echo '<li class="nav-item"><a class="nav-link"  href="php/scripts/deconnexion.php">Déconnexion</a></li>';
    }
    
  }
  // ==========================================================================
?>
