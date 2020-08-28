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
  // revision : 10-jun-2019 pchevaillier@gmail.com + menu_indisponibilites
  // revision : 25-dec-2019 pchevaillier@gmail.com impact refonte calendrier
  // revision : 29-dec-2019 pchevaillier@gmail.com reorganisation items menu
  // --------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // - variables pour acronyme club et lien home-page
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/menu_navigation.php';
  require_once 'php/metier/calendrier.php';
  
  // ==========================================================================
  class Menu_Application extends Menu_Navigation {
    
    // regles 'metier' pour le controle de ce qu'il est permis de faire
    // en fonction du profil de connexion (informations sur la session active)
    private $session_admin = false;
    private $session_pers = false;
    private $session_club = false;
    private $membre_actif = false;
    private $jour;
    
    public function initialiser() {
      $this->session_admin = isset($_SESSION['adm']) && $_SESSION['adm'];
      $this->session_pers = isset($_SESSION['prs']) && $_SESSION['prs'];
      $this->session_club = ! $this->session_pers;
      $this->membre_actif = $this->session_pers && isset($_SESSION['usr']) && isset($_SESSION['act']);
      
      $this->jour = isset($GET['j']) ? new Instant($GET['j']): Calendrier::aujourdhui();
    }
    
    private function afficher_menu_club() {
      echo ' <li class="nav-item dropdown">';
      echo '<a class="nav-link dropdown-toggle" href="#" id="mnu_club" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Club</a>';
      echo ' <div class="dropdown-menu" aria-labelledby="mnu-club">';
      echo '<a class="dropdown-item" href="club.php">Info club</a>';
      echo '<a class="dropdown-item" href="permanences.php">Permanences</a>';
      if ($this->session_admin) {
        echo '<a class="dropdown-item" href="equipe_permanence.php">Equipe permanence</a>';
        echo '<a class="dropdown-item" href="sites_activite.php">Sites d\'activité</a>';
      }
      echo '<a class="dropdown-item" href="fermetures_sites.php">Fermetures sites</a>';
      echo '<a class="dropdown-item" href="composantes.php">Composantes club</a>';
      echo '</div></li>';
    }
    
    private function afficher_menu_inscription() {
      echo ' <li class="nav-item dropdown">';
      echo '<a class="nav-link dropdown-toggle" href="#" id="mnu_inscr" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Inscriptions</a>';
      echo ' <div class="dropdown-menu" aria-labelledby="mnu-inscr">';
      if ($this->membre_actif)
        echo '<a class="dropdown-item" href="inscription_individuelle.php?a=ii">Inscription individuelle</a>';
      echo '<a class="dropdown-item" href="inscription_individuelle.php?a=ie">Inscription équipage</a>';
      echo '<a class="dropdown-item" href="agendas.php">Agendas</a>';
      echo '</div></li>';
    }
    
    private function afficher_menu_supports_activite() {
      echo ' <li class="nav-item dropdown">';
      echo '<a class="nav-link dropdown-toggle" href="#" id="mnu_support" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Supports activités</a>';
      echo ' <div class="dropdown-menu" aria-labelledby="mnu_support">';
      echo '<a class="dropdown-item" href="indisponibilites.php">Indisponibilités supports</a>';
      echo '<a class="dropdown-item" href="https://docs.google.com/spreadsheets/d/14zDfgiiELgDnSE4GkX0tpoB2RK1tlmWS3hywiklecFc/edit#gid=795898690">Signalements anomalie</a>';
     /* if ($this->session_admin) {
        echo '<a class="dropdown-item" href="motifs_indispo_support.php">Motifs indisponibilités</a>';
      }
      */
      echo '<a class="dropdown-item" href="supports_activite.php">Supports activités</a>';
      /*
      if ($this->session_admin) {
        echo '<a class="dropdown-item" href="page_temporaire.php">Types de support d\'activité</a>';
      }
       */
      echo '</div></li>';
    }
    
    private function afficher_menu_personnes() {
      echo ' <li class="nav-item dropdown">';
      echo '<a class="nav-link dropdown-toggle" href="#" id="mnu_prs" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Personnes</a>';
      echo ' <div class="dropdown-menu" aria-labelledby="mnu_prs">';
      echo '<a class="dropdown-item" href="personnes.php?a=l&act=1&cnx=1">Liste personnes</a>';
      if ($this->session_club || $this->session_admin) {
        /*
         * Acces au formulaire pour l'enregistrement d'un nouveau membre du club
         * a = c :
         *   action de creation d'un nouveau membre
         * o = n :
         *   objet de l'action est un nouveau membre (pas encore cree a ce stade)
         */
        echo '<a class="dropdown-item" href="membre.php?a=c&o=n">Enregistrement nouveau</a>';
      }
      if ($this->session_admin) {
        echo '<a class="dropdown-item" href="debutants.php">Débutants > Confirmés</a>';
        //echo '<a class="dropdown-item" href="page_temporaire.php">Listes visiteurs</a>';
      }
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
      if ($this->membre_actif)
        echo '<li class="nav-item"><a class="nav-link" href="accueil_perso.php">Accueil</a></li>';
      else if ($this->session_club)
        echo '<li class="nav-item"><a class="nav-link" href="accueil_club.php">Accueil</a></li>';
        
      echo '<li class="nav-item"><a class="nav-link" href="activites.php?a=l&j=' . $this->jour->valeur_cle_date() . '">Sorties</a></li>';
      
      //if (!isset($_SESSION['prs']) || (isset($_SESSION['prs']) && isset($_SESSION['act'])))
      if ($this->session_club || $this->membre_actif)
        $this->afficher_menu_inscription();

      $this->afficher_menu_club();
      $this->afficher_menu_personnes();
      $this->afficher_menu_supports_activite();
      /*
      if ($this->session_admin) {
          $this->afficher_menu_administration();
      }
      */
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
