<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : definition du menu de navigation de l'application
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin-fichier.php>
  // dependances : bootstrap 4.x, variables de session (identification_verif.php)
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  //              bootstrap 4.3.1
  // --------------------------------------------------------------------------
  // creation : 14-oct-2018 pchevaillier@gmail.com
  // revision : 29-dec-2018 pchevaillier@gmail.com deconnexion
  // revision : 11-mar-2019 pchevaillier@gmail.com $_SESSION['prs'] pas necessairement defini
  // revision : 07-mai-2019 pchevaillier@gmail.com logique / affichage item
  // revision : 23-mai-2019 pchevaillier@gmail.com + afficher_menu_club
  // --------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // - variables pour acronyme club et lien home-page
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/menu_navigation.php';
  
  // ==========================================================================
  class Menu_Application extends Menu_Navigation {
    
    // regles 'metier' pour le controle de ce qu'il est permis de faire
    // en fonction du profil de connexion (informations sur la session active)
    private $session_admin = false;
    private $session_pers = false;
    private $session_club = false;
    private $membre_actif = false;
    
    public function initialiser() {
      $this->session_admin = isset($_SESSION['adm']) && $_SESSION['adm'];
      $this->session_pers = isset($_SESSION['prs']) && $_SESSION['prs'];
      $this->session_club = ! $this->session_pers;
      $this->membre_actif = $this->session_pers && isset($_SESSION['act']);
    }
    
    private function afficher_menu_club() {
      
      echo ' <li class="nav-item dropdown">';
      echo '<a class="nav-link dropdown-toggle" href="#" id="mnu_club" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Club</a>';
      echo ' <div class="dropdown-menu" aria-labelledby="mnu-club">';
      echo '<a class="dropdown-item" href="page_temporaire.php">Info club</a>';
      echo '<a class="dropdown-item" href="composantes.php">Composantes</a>';
      echo '</div></li>';
    }
    
    private function afficher_menu_inscription() {
      echo ' <li class="nav-item dropdown">';
      echo '<a class="nav-link dropdown-toggle" href="#" id="mnu_inscr" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Inscriptions</a>';
      echo ' <div class="dropdown-menu" aria-labelledby="mnu-inscr">';
      //if (isset($_SESSION['prs']))
      if ($this->membre_actif)
          echo '<a class="dropdown-item" href="page_temporaire.php">Individuelle</a>';
      
      echo '<a class="dropdown-item" href="page_temporaire.php">Equipage</a>';
      echo '</div></li>';
    }
    
    private function afficher_menu_administration() {
      
      echo ' <li class="nav-item dropdown">';
      echo '<a class="nav-link dropdown-toggle" href="#" id="mnu_admin" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Administration</a>';
      echo ' <div class="dropdown-menu" aria-labelledby="mnu-admin">';
      echo '<a class="dropdown-item" href="page_temporaire.php">Equipe permanences</a>';
      echo '<a class="dropdown-item" href="debutants.php">Débutants</a>';
      echo '<a class="dropdown-item" href="page_temporaire.php">Visiteurs</a>';
      echo '</div></li>';
    }
    
    protected function afficher_corps() {
    
      echo '<li class="nav-item"><a class="nav-link" href="page_temporaire.php">Accueil</a></li>';
       $this->afficher_menu_club();
      
      //if (!isset($_SESSION['prs']) || (isset($_SESSION['prs']) && isset($_SESSION['act'])))
      if ($this->session_club || $this->membre_actif)
          $this->afficher_menu_inscription();

      echo '<li class="nav-item"><a class="nav-link" href="#">Sorties</a></li>';
      echo '<li class="nav-item"><a class="nav-link" href="agendas.php">Agendas</a></li>';
      echo '<li class="nav-item"><a class="nav-link" href="permanences.php">Permanences</a></li>';
      echo '<li class="nav-item"><a class="nav-link" href="#">Indisponibilités</a></li>';
      echo '<li class="nav-item"><a class="nav-link" href="personnes.php?a=l&act=1&cnx=1">Personnes</a></li>';
      echo '<li class="nav-item"><a class="nav-link" href="#">Bateaux</a></li>';
      
      if ($this->session_club || $this->session_admin) {
        /*
         * Acces au formulaire pour l'enregistrement d'un nouveau membre du club
         * a = c :
         *   action de creation d'un nouveau membre
         * o = n :
         *   objet de l'action est un nouveau membre (pas encore cree a ce stade)
         */
        echo '<li class="nav-item"><a class="nav-link" href="membre.php?a=c&o=n">Nouveau</a></li>';
      }
    
      if ($this->session_admin) {
          $this->afficher_menu_administration();
      }
      
      if ($this->session_pers) {
        /*
         * Acces au formulaire pour la modification de ses donnees personnelles
         * a = m :
         *  action modification des informations relative a la personne
         * o = u :
         *   l' objet sur lequel porte l'action est l'utilisateur connecte
         * mbr :
         *   code de la personne
         */
          echo '<li class="nav-item"><a class="nav-link" href="membre.php?a=m&o=u&mbr=' . $_SESSION['usr'] . '">' .  htmlspecialchars($_SESSION['n_usr']) . '</a></li>';
      }
      echo '<li class="nav-item"><a class="nav-link"  href="php/scripts/deconnexion.php">Déconnexion</a></li>';
    }
    
  }
  // ==========================================================================
?>
