<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : controle des proprietes de la session / identif. connexion
  // copyright (c) 2018 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - include
  //   premiere instruction sur toutes les pages sauf celle de connexion
  //   ainsi que le script de verificcation de l'identite
  // dependances : 
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 14-oct-2018 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // -
  // a faire :
  // - remplacer utilisateur par login ?
  // - creer un objet profil (singleton ?)
  // ==========================================================================
  session_start();
  if (!isset($_SESSION['utilisateur']) && !isset($_SESSION['club'])) {
    header("location: index.php");
    die();
  }
  // ==========================================================================
?>
