<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : traitement requete / mise a jour info sur support activite (json)
  // copyright (c) 2018-2024 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - pour traitement requete ajax
  // dependances : script qui lance cette requete : requete_maj_status_cdb.js
// utilise avec :
// - depuis 2023 :
//   PHP 8.2 sur macOS 13.x
//   PHP 8.1 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 29-aug-2020 pchevaillier@gmail.com
  // revision : 08-jun-2024 pchevaillier@gmail.com amelioration code
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
  require_once 'php/metier/support_activite.php';
  require_once 'php/bdd/enregistrement_support_activite.php';
  // --------------------------------------------------------------------------
 
  // --- informations fournies dans la requete
  //     Le code du support d'activite
  // on recoit :
  $code = (isset($_GET['code'])) ? $_GET['code'] : 0;
  $status = 0;

  if (isset($_GET['statut']) && preg_match('/[01]/', $_GET['statut']))
    $statut = $_GET['statut'];
  else {
    $donnee = array('code' => $code, 'status' => $status);
    $resultat_json = json_encode($donnee);
    echo $resultat_json;
    die();
  }
  // --- Recherche des informations sur le support d'activite
  //     dans la base de donnees
    
  $support = new Support_Activite($code);
  $enregistrement = new Enregistrement_Support_Activite();
  $enregistrement->def_support_activite($support);
  
  $trouve = $enregistrement->lire_identite();
 
  // --- Mise en forme des informations avant retour
  
  if (!$trouve) {
    $donnee = array('code' => $code, 'msg' => 'enregistrement introuvable');
  } else {
    $enregistrement->modifier_actif($code, $statut);
    
    // Informations utiles pour identifier le support
    $id = $enregistrement->support_activite()->nom();
  
    // message indiquant le resultat de l'action
    $msg = "";
    if ($enregistrement->support_activite()->est_actif())
      $msg = "est maintenant actif, c'est-à-dire qu'il est utilisable dans Resabel.";
    else
      $msg = "n'est maintenant plus actif, c'est à dire que l'on ne peut plus l'utiliser dans Resabel";
    $donnee = array('id' => $id, 'msg' => $msg);
  }
  
  // --- Reponse a la requete
  
  $resultat_json = json_encode($donnee);
  echo $resultat_json;
  exit();
  
  // ==========================================================================
?>
