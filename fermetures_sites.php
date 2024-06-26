<?php
  include('php/utilitaires/controle_session.php');
  include('php/utilitaires/definir_locale.php');
 ?>
<!DOCTYPE html>
  <html lang="fr">
    <?php
      // ======================================================================
      // contexte : Resabel - systeme de REServation de Bateaux En Ligne
      // description : page affichage - gestion
      //               des eventuellesfermetures de sites d'activite
      // copyright (c) 2018-2019 AMP. Tous droits reserves.
      // ----------------------------------------------------------------------
      // utilisation : navigateur web
      // dependances : codes enregistres dans la table rsbl_types_indisponibilite
      // teste avec : PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur hebergeur web
      // ----------------------------------------------------------------------
      // creation : 10-jun-2019 pchevaillier@gmail.com
      // revision : 30-acr-2024 pchevaillier@gmail.com
      // ----------------------------------------------------------------------
      // commentaires :
      //  - fontionnalité très similaire à la gestion des indisponibilites
      //    des supports d'activite. Parametrage du type d'indisponibilite. 
      // attention :
      // a faire :
      // ======================================================================
      
      set_include_path('./');
      
      // ----------------------------------------------------------------------
      // verification des parametres passes lors du chargement de la page
      // aucun ici
     
      // ----------------------------------------------------------------------
      // --- connection a la base de donnees
      include 'php/bdd/base_donnees.php';
      
      // --- Information sur le site Web
      require_once 'php/bdd/enregistrement_site_web.php';
      
      if (isset($_SESSION['swb']))
        new Enregistrement_site_web($_SESSION['swb']);
      
      // --- Classe definissant la page a afficher
      require_once 'php/pages/page_indisponibilites.php';

require_once 'php/bdd/enregistrement_indisponibilite.php';

      // ----------------------------------------------------------------------
      // --- Creation dynamique de la page
      $feuilles_style = array();
      $feuilles_style[] = "css/resabel_ecran.css";
      $nom_site = Site_Web::accede()->sigle() . " Resabel";
      $page = new Page_Indisponibilites($nom_site,
                                        "Fermetures sites d'activité",
                                        Enregistrement_Indisponibilite::CODE_TYPE_INDISPO_SITE,
                                        $feuilles_style);
      
      // --- Affichage de la page
      $page->initialiser();
      $page->afficher();
      // ======================================================================
    ?>
  </html>
