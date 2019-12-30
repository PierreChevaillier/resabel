<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : definit les informations de localisation (langue, fuseau horaire)
  // copyright (c) 2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - include
  //   au debut des scripts qui utilisent les dates ou heures
  // dependances : 
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.3 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 25-dec-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // -
  // a faire :
  // ==========================================================================
  date_default_timezone_set('Europe/Paris'); // peut etre change ailleurs
  setlocale(LC_ALL, 'fr_FR.utf-8', 'french');
  // ==========================================================================
?>
