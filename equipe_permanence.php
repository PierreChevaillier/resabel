<?php
  include('php/utilitaires/controle_session.php');
  include('php/utilitaires/definir_locale.php');
 ?>
<!DOCTYPE html>
  <html lang="fr">
    <?php
      // ======================================================================
      // contexte : Resabel - systeme de REServation de Bateaux En Ligne
      // description : page pour affichage / gestion des membres de l'équipe
      //               assurant les permanences.
      // copyright (c) 2018-2020 AMP. Tous droits reserves.
      // ----------------------------------------------------------------------
      // utilisation : navigateur web
      // dependances : (cf. require_once)  - valeur variables $_SESSION
      // teste avec : PHP 7.1 sur macOS 10.14 ; PHP 7.3 sur hebergeur web
      // ----------------------------------------------------------------------
      // creation : 30-dec-2019 pchevaillier@gmail.com
      // revision :
      // ----------------------------------------------------------------------
      // commentaires :
      //  - En construction
      // attention :
      //  - pas completement operationnel
      // a faire :
      //  - completer les fonctionnalites
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
      require_once 'php/elements_page/generiques/entete_contenu_page.php';
      require_once 'php/metier/calendrier.php';
      
      // ----------------------------------------------------------------------
      // --- Creation dynamique de la page
      $feuilles_style = array();
      $feuilles_style[] = "css/resabel_ecran.css";
      $nom_site = Site_Web::accede()->sigle() . " Resabel";
      $page = new Page_Menu($nom_site, "équipe permanence", $feuilles_style);
      
      $element = new Entete_Contenu_Page();
      $element->def_titre("Equipe permanence");
      $page->ajoute_element_haut($element);
      
      // --- Contenu temporaire
      $info = new Element_Code();
      $code_html = '<div>' . PHP_EOL;

      $maintenant = Calendrier::maintenant();
      $aujourdhui = $maintenant->jour();
      $code_html = $code_html . '<p>Nous sommes le ' . $aujourdhui->date_texte() . '</p>' . PHP_EOL;
      $code_html = $code_html . '</div>' . PHP_EOL;
      $info->def_code($code_html);
      $page->ajoute_contenu($info);
      
      // --- Explications sur ce qu'il y aura sur la page
      $doc = new Element_Code();
      $code_html = '<div>' . PHP_EOL;
      $code_html = $code_html . '<p>Cette page affiche la composition de l\'équipe assurant les permanences du club</p>' . PHP_EOL;
      $code_html = $code_html . '<p>Elle  est très similaire à celle de Resabel V1.</p>' . PHP_EOL;
      $code_html = $code_html . '<p>Si l\'utlisateur est administrateur, il peut la modifier.</p>' . PHP_EOL;
      $code_html = $code_html . '<p>Remarque : cette fonctionnalité pourrait être assurée par la page de gestion des composantes. </p>' . PHP_EOL;
      $code_html = $code_html . '</div>' . PHP_EOL;
      $doc->def_code($code_html);
      $page->ajoute_contenu($doc);
      
      // --- Affichage de la page
      $page->initialiser();
      $page->afficher();
      // ======================================================================
    ?>
  </html>
