<?php
  // ===========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : reset les variables de session & retour a la page d'accueil
  // copyright (c) 2018 AMP. All rights reserved.
  // --------------------------------------------------------------------------
  // utilisation : php
  // - include <chemin_vers_ce_fichier.php>
  // - option 'Deconnexion' du menu de l'application
  // dependances : identification_verif.php
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  // ---------------------------------------------------------------------------
  // creation : 29-dec-2018 pchevaillier@gmail.com
  // revision :
  // ---------------------------------------------------------------------------
  // commentaires :
  // les variables de session sont initialisees dans identification_verif.php
  // attention :
  // a faire :
  // ===========================================================================
  session_start(); // doit etre la premiere instruction

  unset($_SESSION['clb']);
  unset($_SESSION['n_clb']);
  unset($_SESSION['prs']);
  unset($_SESSION['usr']);
  unset($_SESSION['n_usr']);
  unset($_SESSION['act']);
  unset($_SESSION['cdb']);
  unset($_SESSION['prm']);
  unset($_SESSION['adm']);
  
  header("location: ../../index.html");
  exit();
  // ==========================================================================
  ?>
