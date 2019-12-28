<?php
  include('php/utilitaires/controle_session.php');
  include('php/utilitaires/definir_locale.php');
 ?>
<!DOCTYPE html>
  <html lang="fr">
    <?php
      // ======================================================================
      // contexte : Resabel - systeme de REServation de Bateaux En Ligne
      // description : page pour l'inscription de l'utilisateur connecte a
      //               une seance d'activite : formulaire de recherche
      //               de disponibilite d'un support disponible pour une plage
      //               de creneaux horaires
      // copyright (c) 2018-2019 AMP. Tous droits réserves.
      // ----------------------------------------------------------------------
      // utilisation : navigateur web
      // dependances : valeurs variable $_SESSION
      // teste avec : PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur hebergeur web
      // ----------------------------------------------------------------------
      // creation : 10-jul-2019 pchevaillier@gmail.com
      // revision :
      // ----------------------------------------------------------------------
      // commentaires :
      // attention :
      // a faire :
      // ======================================================================
      
      set_include_path('./');
      
      // ----------------------------------------------------------------------
      // Verification acces a cette page
      // Fonctionnalité uniquement offerte a un utilisateur connecte
      // identifie comme un membre actif
      // Normalement le menu de l'application ne permet pas d'arriver ici
      // si ce n'est pas le cas. 
      
      $possible = isset($_SESSION['prs']) && $_SESSION['prs'] && $_SESSION['usr'] && $_SESSION['act'];
      if (!$possible) {
        header("location: index.php");
        die("erreur : valeur non definie");
      }
      
      // -----------------------------------------------------------------------
      // --- connection a la base de donnees
      include 'php/bdd/base_donnees.php';
      
      // --- Information sur le site Web
      require_once 'php/bdd/enregistrement_site_web.php';
      
      if (isset($_SESSION['swb']))
        new Enregistrement_site_web($_SESSION['swb']);
      
      // --- Classe definissant la page a afficher
      require_once 'php/pages/page_inscription_individuelle.php';

      // --- Classes des elements de la page

      // ----------------------------------------------------------------------
      // --- Creation dynamique de la page
      $feuilles_style = array();
      $feuilles_style[] = "css/resabel_ecran.css";
      $nom_site = Site_Web::accede()->sigle() . " Resabel";
      $page = new Page_Inscription_Individuelle($nom_site, "Inscription individuelle", $feuilles_style);
      
      // --- Affichage de la page
      $page->initialiser();
      $page->afficher();
      // ======================================================================
    ?>
  </html>
