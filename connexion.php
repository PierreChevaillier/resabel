<!DOCTYPE html>
  <html lang="fr">
    <?php
      // ======================================================================
      // contexte : Resabel - systeme de REServation de Bateaux En Ligne
      // description : page web vide pour test (modele)
      // ----------------------------------------------------------------------
      // utilisation : navigateur web
      // dependances :
      // teste avec : PHP 5.5.3 sur Mac OS 10.11 ; PHP 7.0 sur hebergeur web
      // copyright (c) 2017-2018 AMP. Tous droits réserves.
      // ----------------------------------------------------------------------
      // creation : 02-oct-2017 pchevaillier@gmail.com
      // revision : 19-jan-2018 pchevaillier@gmail.com mise en forme
      // revision : 17-jan-2018 pchevaillier@gmail.com resabel V2
      // ----------------------------------------------------------------------
      // commentaires :
      //  -
      // attention :
      // a faire :
      // ======================================================================
      
      set_include_path('./');
      
      // --- Classe définissant la page a afficher
      require_once 'php/pages/page_connexion.php';

      // --- Classes des elements de la page
      require_once 'php/elements_page/generiques/element.php';

      // ----------------------------------------------------------------------
      // --- Creation dynamique de la page
      $feuilles_style = array();
      //$feuilles_style[] = "css/resabel_ecran.css";
      $page = new Page_Connexion("AMP - Resabel", "connexion", $feuilles_style);
      
      $info = new Element_Code();
      $code_html = "<div class=\"alert alert-info\" role=\"alert\">Vous devez vous identifez pour accéder à ce service</div>";
      $info->def_code($code_html);
      $page->ajoute_contenu($info);
      
      // --- Affichage de la page
      $page->initialiser();
      $page->afficher();
      // ======================================================================
    ?>
  </html>
