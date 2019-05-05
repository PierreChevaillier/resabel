<?php
  include('php/utilitaires/controle_session.php');
 ?>
<!DOCTYPE html>
  <html lang="fr">
    <?php
      // ======================================================================
      // contexte : Resabel - systeme de REServation de Bateaux En Ligne
      // description : page pour lancer l'action de supression du statut
      // de devbutant des actuels débutants (nouveaux)
      // copyright (c) 2018-2019 AMP. Tous droits reserves.
      // ----------------------------------------------------------------------
      // utilisation : navigateur web
      // dependances :
      // teste avec : PHP 7.1 sur Mac OS 10.14 ;
      //              PHP 7.0 sur hebergeur web
      // ----------------------------------------------------------------------
      // creation : 14-oct-2018 pchevaillier@gmail.com
      // revision :
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
      require_once 'php/elements_page/generiques/entete_section.php';
      require_once 'php/elements_page/generiques/modal.php';
      
      // ----------------------------------------------------------------------
      // --- Creation dynamique de la page
      $feuilles_style = array();
      $feuilles_style[] = "css/resabel_ecran.css";
      $nom_site = Site_Web::accede()->sigle() . " Resabel";
      $page = new Page_Menu($nom_site, "débutants", $feuilles_style);
      $page->javascripts[] ="js/requete_maj_niveau_debutants.js";
      
      $banniere = new Entete_Section();
      $banniere->def_titre("Suppression repérage débutants");
      $page->ajoute_contenu($banniere);
      
      $afficheur_action = new Element_Modal();
      $afficheur_action->def_id('aff_msg_act');
      $page->ajoute_contenu($afficheur_action);
      
      $info = new Element_Code();
      $code_html = "\n<div class=\"rsbl-msg-action\"><p class=\"lead\">Cette opération supprime le repérage des nouveaux en tant que tel. </p><p><strong>Attention</strong> : Elle ne peut pas être annulée.</p></div>\n";
      $code_html = $code_html . "\n<button class=\"btn btn-primary\" data-toggle=\"modal\" data-target=\"#aff_msg_act\" onclick=\"return requete_maj_niveau_debutants('aff_msg_act');\">Exécuter action</button>\n";
      $info->def_code($code_html);
      $page->ajoute_contenu($info);
      
      // --- Affichage de la page
      $page->initialiser();
      $page->afficher();
      // ======================================================================
    ?>
  </html>
