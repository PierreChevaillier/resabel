<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : traitement requete / information personne (json)
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - pour traitement requete ajax
  // dependances : script qui lance cette requete : requete_maj_status_cdb.js
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 01-mai-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // -
  // a faire :
  // -
  // ==========================================================================
  
  set_include_path('./../../');
  
  include('php/utilitaires/controle_session.php');
  
  // --- connection a la base de donnees (et instantiation du 'handler')
  include_once 'php/bdd/base_donnees.php';
  
  // --- classes utilisees
  require_once 'php/bdd/enregistrement_membre.php';
  // --------------------------------------------------------------------------
  
  Enregistrement_Membre::modifier_niveaux(1 /* actuel */, 2 /* nouveau */);
  
  $donnee = array('status' => 'ok'); // ne sert a rien
  
  // --- Reponse a la requete
  $resultat_json = json_encode($donnee);
  echo $resultat_json;
  exit();
  // ==========================================================================
?>
