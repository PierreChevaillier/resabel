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
      // utilisation : navigateur web - test - bouchon
      // dependances :
      // teste avec : PHP 7.0 sur Mac OS 10.14 ; PHP 7.0 sur hebergeur web
      // copyright (c) 2018 AMP. Tous droits réserves.
      // ----------------------------------------------------------------------
      // creation : 14-oct-2018 pchevaillier@gmail.com
      // revision : 26-dec-2019 pchevaillier@gmail.com test calendrier
      // ----------------------------------------------------------------------
      // commentaires :
      //  -
      // attention :
      // a faire :
      // ======================================================================
      
      set_include_path('./');
      
      // --- connection a la base de donnees
      include 'php/bdd/base_donnees.php';
      
      // --- Information sur le site Web
      require_once 'php/bdd/enregistrement_site_web.php';
      
      if (isset($_SESSION['swb']))
        new Enregistrement_site_web($_SESSION['swb']);

      
      // --- Classe definissant la page a afficher
      require_once 'php/elements_page/specifiques/page_menu.php';

      // --- Classes des elements de la page
      require_once 'php/elements_page/generiques/element.php';
      require_once 'php/metier/calendrier.php';
      
      // ----------------------------------------------------------------------
      // --- Creation dynamique de la page
      $feuilles_style = array();
      $feuilles_style[] = "css/resabel_ecran.css";
      $nom_site = Site_Web::accede()->sigle() . " Resabel";
      $page = new Page_Menu($nom_site, "temporaire", $feuilles_style);
      
      $info = new Element_Code();
      $code_html = "<div class=\"alert alert-info\" role=\"alert\">Page temporaire</div>" . PHP_EOL;
      
      // En guise de test pour la gestion des dates et heures.
      $maintenant = Calendrier::maintenant();
      $aujourdhui = $maintenant->jour();
      $code_html = $code_html . '<div><p>Nous sommes le ' . utf8_encode($aujourdhui->date_texte()) . '<br />';
      $code_html = $code_html . 'Cette page a été générée le ' . utf8_encode($maintenant->date_texte_abbr()) . ' à ' . $maintenant->heure_texte() . '</p></div>' . PHP_EOL;
      $info->def_code($code_html);
      $page->ajoute_contenu($info);
      
      // --- Affichage de la page
      $page->initialiser();
      $page->afficher();
      // ======================================================================
    ?>
  </html>
