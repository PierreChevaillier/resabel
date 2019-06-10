<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : Classe Seance Activite
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : 
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 09-jun-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // -
  // a faire :
  // -
  // ==========================================================================

  class Seance_activite {
    $site;
    $support;
    $intervalle_programme;
    //$intervalle_realise;
    $chef_de_bord;
    $equipiers = array();
  }
  
  //classes : sortie en mer, seance ergo, regate, randonnee, seance_stage...
  
  class Seance_Personnelle {
    $seance = null;
    $personne;
    $informations = "";
    $programme ="";
    $forme = "";
    $condition_pratique = "";
  }
  // ==========================================================================
?>
