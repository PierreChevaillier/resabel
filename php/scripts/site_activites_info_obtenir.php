<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : traitement requete (json) pour obtenir les informations
  //               necessaire pour le formualire de recherche de disponibilite
  //               de supports d'activite pour un site, a une date donnee
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - pour traitement requete ajax
  // dependances : javascript qui lance cette requete
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 07-sep-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  //  - pour l'instant c'est un bouchon ...
  // attention :
  // a faire :
  // ==========================================================================
  
  set_include_path('./../../');
  
  include('php/utilitaires/controle_session.php');
  
  // --- connection a la base de donnees (et instantiation du 'handler')
  include_once 'php/bdd/base_donnees.php';
  
  // --- classes utilisees
  require_once 'php/metier/support_activite.php';
  require_once 'php/bdd/enregistrement_support_activite.php';
  // --------------------------------------------------------------------------
  
  if (isset($_GET['sa'])) {
    $code_site = $_GET['sa'];
  }

  if (isset($_GET['j'])) {
    $jour = $_GET['j'];
  }
  
  $supports = null;
  Enregistrement_Support_Activite::collecter("code_site_base = " . $code_site . " AND actif = 1 ", " type DESC, code ASC", $supports);
  
  $choix_type_support = array();
  foreach ($supports as $support)
    $choix_type_support[$support->type->code()] = $support->type->nom();
  $types_support_json = json_encode($choix_type_support);
  
  $choix_support = array();
  foreach ($supports as $support)
    $choix_support[$support->code()] = $support->identite_texte();
  $supports_json = json_encode($choix_support);
  
  $donnees = array('s' => $supports_json, 'ts' => $types_support_json, 'pc' => '8:00');
  
  // --- Reponse a la requete
  $resultat_json = json_encode($donnees);
  echo $resultat_json;
  exit();
  // ==========================================================================
?>
