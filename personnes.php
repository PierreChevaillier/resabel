<?php
  include('php/utilitaires/controle_session.php');
 ?>
<!DOCTYPE html>
  <html lang="fr">
    <?php
      // ======================================================================
      // contexte : Resabel - systeme de REServation de Bateaux En Ligne
      // description : page affichage du carnet d'adresses du club
      // ----------------------------------------------------------------------
      // utilisation : navigateur web
      // dependances :
      // teste avec : PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur hebergeur web
      // copyright (c) 2018-2019 AMP. Tous droits réserves.
      // ----------------------------------------------------------------------
      // creation : 03-mar-2019 pchevaillier@gmail.com
      // revision : 16-mar-2019 pchevaillier@gmail.com nveau nom, Page_Personnes
      // revision : 30-avr-2019 pchevaillier@gmail.com criteres de selection
      // ----------------------------------------------------------------------
      // commentaires :
      //  -
      // attention :
      // a faire :
      // ======================================================================
      
      set_include_path('./');
      
      // ----------------------------------------------------------------------
      // verification des parametres passes lors du chargement de la page
      
      // Verifie l'action demandee
      // parametre obligatoire pas de valeur par defaut)
      if (!isset($_GET['a']))
        die("erreur : action non definie");
      if (!preg_match('/[l]/', $_GET['a']))
        die("erreur : action invalide");
      
      // parametres optionnels: criteres de selection pour l'affichage
      // de la liste des personnes
      $criteres_selection = array();
      if (isset($_GET['act']) && preg_match('/[01]/', $_GET['act']))
          if (isset($_SESSION['adm']))
            // seuls les utilisateurs avec le privilege 'admin' peuvent
            // acceder aux informations sur les comptes inactifs
            $criteres_selection['act'] = $_GET['act'];
          else
            $criteres_selection['act'] = 1; // pas admin => que les actifs
      if (isset($_GET['cnx']) && preg_match('/[01]/', $_GET['cnx']))
          $criteres_selection['cnx'] = $_GET['cnx'];
      
      if (isset($_POST['prn']) && $_POST['prn'] != "")
        $criteres_selection['prn'] = $_POST['prn'];
      
      if (isset($_POST['nom']) && $_POST['nom'] != "")
        $criteres_selection['nom'] = $_POST['nom'];
      
      if (isset($_POST['cmn']) && $_POST['cmn'] != 0)
        $criteres_selection['cmn'] = $_POST['cmn'];
      
      if (isset($_POST['cdb'])&& $_POST['cdb'] != 0)
        $criteres_selection['cdb'] = $_POST['cdb'];
      
      if (isset($_POST['niv'])&& $_POST['niv'] != 0)
        $criteres_selection['niv'] = $_POST['niv'];
      
      // ----------------------------------------------------------------------
      // --- connection a la base de donnees
      include 'php/bdd/base_donnees.php';
      
      // --- Information sur le site Web
      require_once 'php/bdd/enregistrement_site_web.php';
      
      if (isset($_SESSION['swb']))
        new Enregistrement_site_web($_SESSION['swb']);
      
      // --- Classe definissant la page a afficher
      require_once 'php/pages/page_personnes.php';

      // ----------------------------------------------------------------------
      // --- Creation dynamique de la page
      $feuilles_style = array();
      $feuilles_style[] = "css/resabel_ecran.css";
      $nom_site = Site_Web::accede()->sigle() . " Resabel";
      $page = new Page_Personnes($nom_site, "Carnet addresses", $feuilles_style);
      $page->def_id("pg_prs");
      
      $page->criteres_selection = $criteres_selection;
      
      // --- Affichage de la page
      $page->initialiser();
      
      $page->afficher();
      // ======================================================================
    ?>
  </html>
