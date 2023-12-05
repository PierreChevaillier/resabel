<?php
  include('php/utilitaires/controle_session.php');
  include('php/utilitaires/definir_locale.php');
 ?>
<!DOCTYPE html>
  <html lang="fr">
    <?php
      // ======================================================================
      // contexte : Resabel - systeme de REServation de Bateaux En Ligne
      // description : page pour la connexion au systeme : identification
      // ----------------------------------------------------------------------
      // utilisation : navigateur web
      // dependances :
      // teste avec : PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur hebergeur web
      // copyright (c) 2018 AMP. Tous droits réserves.
      // ----------------------------------------------------------------------
      // creation : 06-dec-2018 pchevaillier@gmail.com
      // revision : 16-dec-2018 pchevaillier@gmail.com info / site web
      // ----------------------------------------------------------------------
      // commentaires :
      //  -
      // attention :
      // a faire :
      // ======================================================================
      
      set_include_path('./');
      
      // --------------------------------------------------------------------------
      // --- connection a la base de donnees
      include 'php/bdd/base_donnees.php';
      
      // --- Information sur le site Web
      require_once 'php/bdd/enregistrement_site_web.php';
      
      if (isset($_SESSION['swb']))
        new Enregistrement_site_web($_SESSION['swb']);
      
      // --- Classe definissant la page a afficher
      require_once 'php/pages/page_membre.php';

      // --- Classes des elements de la page
      //require_once 'php/elements_page/generiques/element.php';

      // ----------------------------------------------------------------------
      // --- Creation dynamique de la page
      $feuilles_style = array();
      $feuilles_style[] = "css/resabel_ecran.css";
      //$feuilles_style[] = "./../jquery-ui/1.13.2/jquery-ui.css";
      $nom_site = Site_Web::accede()->sigle() . " Resabel";
      $page = new Page_Membre($nom_site, "Information membre", $feuilles_style);
      
$page->ajouter_script('./../jquery/3.6.3/jquery.min.js');
//$page->ajouter_script('./../jquery-ui/1.13.2/jquery-ui.min.js');
      // --- Affichage de la page
      $page->initialiser();
      $page->afficher();
      // ======================================================================
    ?>
  </html>
