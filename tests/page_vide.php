<!DOCTYPE html>
  <html lang="fr">
    <?php
      // ======================================================================
      // contexte : Resabel - systeme de REServation de Bateaux En Ligne
      // description : page web vide pour test (modele)
      // ----------------------------------------------------------------------
      // utilisation : navigateur web
      // dependances :
      // teste avec : PHP 7.1.x sur Mac OS 10.13 ; PHP 7.0 sur hebergeur web
      // copyright (c) 2017-2018 AMP. Tous droits reserves.
      // ----------------------------------------------------------------------
      // creation : 02-oct-2017 pchevaillier@gmail.com
      // revision : 19-jan-2018 pchevaillier@gmail.com mise en forme
      // revision : 17-jan-2018 pchevaillier@gmail.com resabel V2
      // revision : 26-aug-2018 pchevaillier@gmail.com para. feuille(s) de style
      // ----------------------------------------------------------------------
      // commentaires :
      //  -
      // attention :
      // a faire :
      // ======================================================================
      
      set_include_path('./../');
      
      // --- Classe définissant la page a afficher
      require_once 'php/elements_page/generiques/page.php';

      // --- Classes des elements de la page
      require_once 'php/elements_page/generiques/element.php';

      // ----------------------------------------------------------------------
      // --- Creation dynamique de la page
      $feuilles_style = array();
      $feuilles_style[] = "css/resabel_ecran.css";
      $page = new Page_Simple("AMP - Resabel", "page vide"); //, $feuilles_style);
      
      $code_html = "<h1> Page vide</h1>\n<p>Cette page est vide.</p>";
      $corps_page = new Element_Code();
      $corps_page->def_code($code_html);
      $page->ajoute_contenu($corps_page);
      
      // --- Affichage de la page
      $page->initialiser();
      $page->afficher();
      // ======================================================================
    ?>
  </html>
