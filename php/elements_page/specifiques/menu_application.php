<?php
/* ============================================================================
 * Resabel - systeme de REServAtion de Bateau En Ligne
 * Copyright (C) 2024 Pierre Chevaillier
 * contact: pchevaillier@gmail.com 70 allee de Broceliande, 29200 Brest, France
 * ----------------------------------------------------------------------------
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License,
 * or any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 * ----------------------------------------------------------------------------
 * description : definition du menu de navigation de l'application
 * utilisation : php - require_once <chemin_vers_ce_fichier_php>
 * dependances :
 * - bootstrap 5.3
 * - variables $_SESSION (identification_verif.php) et $GET
 * - liens vers documents AMP
 * ----------------------------------------------------------------------------
 * creation : 14-oct-2018 pchevaillier@gmail.com
 * revision : 07-mai-2019 pchevaillier@gmail.com logique / affichage item
 * revision : 17-mar-2023 pchevaillier@gmail.com bootstrap v5.3
 * revision : 22-may-2024 pchevaillier@gmail.com + utilisation Profil_Session
 * revision : 22-may-2024 pchevaillier@gmail.com * afficher_menu_competition
 * revision : 28-aug-2024 pchevaillier@gmail.com + reactivation compte
 * revision : 16-sep-2024 pchevaillier@gmail.com * menu competition AMP
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * - certains liens sont specifiques AMP
 * a faire :
 * - variables pour acronyme club et lien home-page
 * ============================================================================
 */

// --- Classes utilisees
require_once 'php/elements_page/generiques/menu_navigation.php';
require_once 'php/metier/calendrier.php';
require_once 'php/metier/profil_session.php';

// ============================================================================
  class Menu_Application extends Menu_Navigation {
    
    // regles 'metier' pour le controle de ce qu'il est permis de faire
    // en fonction du profil de connexion (informations sur la session active)
    private $session_admin = false;
    private $session_pers = false;
    private $session_club = false;
    private $membre_actif = false;
    private ?Instant $jour;
    
    public function initialiser() {
      $profil = new Profil_Session();
      $this->session_admin = $profil->est_admin();
      $this->session_pers = $profil->est_personne();
      $this->session_club = $profil->est_club();
      $this->membre_actif = $profil->est_membre_actif();
      
      $this->jour = isset($GET['j']) ? new Instant($GET['j']): Calendrier::aujourdhui();
    }
    
    private function afficher_menu_club() {
      echo '<li class="nav-item dropdown">';
      echo '<a class="nav-link dropdown-toggle" href="#" id="mnu_club" role="button" data-bs-toggle="dropdown" aria-expanded="false">Club</a>';
      echo '<ul class="dropdown-menu" aria-labelledby="mnu-club">';
      echo '<li><a class="dropdown-item" href="agendas.php">Calendrier</a></li>';
      echo '<li><a class="dropdown-item" href="permanences.php">Permanences</a></li>';
      if ($this->session_admin) {
        echo '<li><a class="dropdown-item" href="equipe_permanence.php">Equipe permanence</a></li>';
        echo '<li><a class="dropdown-item" href="sites_activite.php">Sites d\'activité</a></li>';
      }
      echo '<li><a class="dropdown-item" href="fermetures_sites.php">Fermetures sites</a></li>';
      //echo '<li><a class="dropdown-item" href="club.php">Info club</a></li>';
      echo '<li><a class="dropdown-item" href="composantes.php">Composantes club</a></li>';
      echo '</ul></li>';
    }
    
    private function afficher_menu_inscription() {
      echo ' <li class="nav-item dropdown">';
      echo '<a class="nav-link dropdown-toggle" href="#" id="mnu_inscr" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Inscriptions</a>';
      echo ' <ul class="dropdown-menu" aria-labelledby="mnu-inscr">';
      if ($this->membre_actif)
        echo '<li><a class="dropdown-item" href="inscription_individuelle.php?a=ii">Inscription individuelle</a>';
      echo '<li><a class="dropdown-item" href="inscription_individuelle.php?a=ie">Inscription équipage</a></li>'; // Meme page que inscription individuelle avec option 'ie'
      echo '<li><a class="dropdown-item" href="agendas.php">Agendas</a></li>';
      echo '</ul></li>';
    }
    
    private function afficher_menu_supports_activite() {
      echo '<li class="nav-item dropdown">';
      echo '<a class="nav-link dropdown-toggle" href="#" id="mnu_support" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Supports activités</a>';
      echo '<ul class="dropdown-menu" aria-labelledby="mnu_support">';
      echo '<li><a class="dropdown-item" href="indisponibilites.php">Indisponibilités supports</a></li>';
      echo '<li><a class="dropdown-item" href="https://docs.google.com/spreadsheets/d/14zDfgiiELgDnSE4GkX0tpoB2RK1tlmWS3hywiklecFc/edit#gid=795898690">Signalements anomalies</a></li>';
     /* if ($this->session_admin) {
        echo '<li><a class="dropdown-item" href="motifs_indispo_support.php">Motifs indisponibilités</a></li>';
      }
      */
      echo '<li><a class="dropdown-item" href="supports_activite.php">Supports activités</a><li>';
      /*
      if ($this->session_admin) {
        echo '<li><a class="dropdown-item" href="page_temporaire.php">Types de support d\'activité</a>'</li>;
      }
       */
      echo '</ul></li>';
    }
    
    private function afficher_menu_personnes() {
      echo '<li class="nav-item dropdown">';
      echo '<a class="nav-link dropdown-toggle" href="#" id="mnu_prs" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Membres</a>';
      echo '<ul class="dropdown-menu" aria-labelledby="mnu_prs">';
      
      echo '<li><a class="dropdown-item" href="personnes.php?a=l&act=1&cnx=1">Liste membres</a></li>';
      
      /*
       * Acces documents inscriptions - licence - assurance
       * ATTENTION : specifique AMP
       */
      echo '<li><a class="dropdown-item" href="https://drive.google.com/drive/folders/1_ZSrgs3q5olRmF7wrI1RhCtQTB4CwrAV" target ="_blank">Docs inscription</a></li>';
      
      if ($this->session_club || $this->session_admin) {
        /*
         * Acces au formulaire pour l'enregistrement d'un nouveau membre du club
         * a = c :
         *   action de creation d'un nouveau membre
         * o = n :
         *   objet de l'action est un nouveau membre (pas encore cree a ce stade)
         */
        echo '<li><a class="dropdown-item" href="membre.php?a=c&o=n">Enregistrement nouveau</a></li>';
      }
      if ($this->session_admin) {
        echo '<li><a class="dropdown-item" href="debutants.php">Débutant.e.s > Confirmé.e.s</a></li>';
        echo '<li><a class="dropdown-item" href="reactivation_comptes.php?a=l&act=0&cnx=0">Réactivation comptes</a></li>';
        //echo '<a class="dropdown-item" href="page_temporaire.php">Listes visiteurs</a>';
      }
      echo '</ul></li>';
    }
      
    private function afficher_menu_administration() {
      echo '<li class="nav-item dropdown">';
      echo '<a class="nav-link dropdown-toggle" href="#" id="mnu_admin" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Administration</a>';
      echo '<ul class="dropdown-menu" aria-labelledby="mnu-admin">';
      echo '<li><a class="dropdown-item" href="page_temporaire.php">Equipe permanences</a></li>';
      echo '<li><a class="dropdown-item" href="debutants.php">Débutants</a></li>';
      echo '<li><a class="dropdown-item" href="page_temporaire.php">Visiteurs</a></li>';
      echo '</ul></li>';
    }

  private function afficher_menu_competitions() {
    echo '<li class="nav-item dropdown">';
    echo '<a class="nav-link dropdown-toggle" href="#" id="mnu_compet" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Compétitions</a>';
    echo '<ul class="dropdown-menu" aria-labelledby="mnu-compet">';
    echo '<li><a class="dropdown-item" href="https://docs.google.com/spreadsheets/d/1NCVO0pP9FDNUNUXjOcKnhu9LAIUaQbqF7Dq4IN8_NV8/edit?usp=sharing" target ="_blank">Inscription régates</a></li>';
    echo '<li><a class="dropdown-item" href=" https://drive.google.com/drive/folders/1XD37ip3r_62DF6ZMz5LS98kzZaDftBUE?usp=sharing" target ="_blank">Avant-programmes</a></li>';
    echo '<li><a  class="dropdown-item" href="https://drive.google.com/drive/folders/1s0s_qEy7ge7se3OLEDvR5tCWlQkLVR1f?usp=sharing" target="_blank">Entrainements</a></li>';
    echo '</ul></li>';
  }
      
    protected function afficher_corps() {
      if ($this->membre_actif)
        echo '<li class="nav-item"><a class="nav-link" href="accueil_perso.php">Accueil</a></li>';
      else if ($this->session_club)
        echo '<li class="nav-item"><a class="nav-link" href="accueil_club.php">Accueil</a></li>';

      if ($this->session_club || $this->membre_actif)
        $this->afficher_menu_inscription();

      /*
       * Affichage du tableau des activites pour le jour courrant
       * a =l : action lecture ; pas de modfication possible
       */
      echo '<li class="nav-item"><a class="nav-link" href="activites.php?a=l&j=' . $this->jour->valeur_cle_date() . '">Sorties</a></li>';
      
      $this->afficher_menu_competitions();
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
// ============================================================================
?>
