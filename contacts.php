<?php
  include('php/utilitaires/controle_session.php');
 ?>
<!DOCTYPE html>
  <html lang="fr">
    <?php
      // ======================================================================
      // contexte : Resabel - systeme de REServation de Bateaux En Ligne
      // description : page affichage des contacts
      // ----------------------------------------------------------------------
      // utilisation : navigateur web
      // dependances :
      // teste avec : PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur hebergeur web
      // copyright (c) 2018-2019 AMP. Tous droits réserves.
      // ----------------------------------------------------------------------
      // creation : 03-mar-2019 pchevaillier@gmail.com
      // revision :
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
      require_once 'php/elements_page/specifiques/page_menu.php';

      // --- Classes des elements de la page
      require_once 'php/elements_page/specifiques/table_personnes.php';

      // ----------------------------------------------------------------------
      // --- Creation dynamique de la page
      $feuilles_style = array();
      $feuilles_style[] = "css/resabel_ecran.css";
      $nom_site = Site_Web::accede()->sigle() . " Resabel";
      $page = new Page_Menu($nom_site, "Contacts", $feuilles_style);
      
      $tableau = new Table_Personnes();
      $tableau->def_page($page);
      $page->ajoute_contenu($tableau);
      
      // --- Affichage de la page
      $page->initialiser();
      $page->afficher();
      // ======================================================================
    ?>
  </html>
