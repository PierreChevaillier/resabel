<?php
  include('php/utilitaires/controle_session.php');
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
      // revision :
      // ----------------------------------------------------------------------
      // commentaires :
      //  -
      // attention :
      // a faire :
      // ======================================================================
      
      set_include_path('./');
      
      // --- Classe definissant la page a afficher
      require_once 'php/pages/page_membre.php';

      // --- Classes des elements de la page
      //require_once 'php/elements_page/generiques/element.php';

      // ----------------------------------------------------------------------
      // --- Creation dynamique de la page
      $feuilles_style = array();
      $feuilles_style[] = "css/resabel_ecran.css";
      $page = new Page_Membre("AMP - Resabel", "Information membre", $feuilles_style);
      
      // --- Affichage de la page
      $page->initialiser();
      $page->afficher();
      // ======================================================================
    ?>
  </html>
