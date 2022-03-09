<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : traitement requete / mise a jour info sur support activite (json)
  // copyright (c) 2018-2020 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - pour traitement requete ajax
  // dependances : script qui lance cette requete : requete_maj_status_cdb.js
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 29-aug-2020 pchevaillier@gmail.com
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
  require_once 'php/metier/support_activite.php';
  require_once 'php/bdd/enregistrement_support_activite.php';
  // --------------------------------------------------------------------------
 
  // --- informations fournies dans la requete
  //     Le code du support d'activite
  // on recoit :
  $code = (isset($_GET['code'])) ? $_GET['code'] : 0;
  
  if (isset($_GET['statut']) && preg_match('/[01]/', $_GET['statut']))
    $statut = $_GET['statut'];
  else
    die();

  // --- Recherche des informations sur le support d'activite
  //     dans la base de donnees
    
  $support = new Support_Activite($code);
  $enregistrement = new Enregistrement_Support_Activite();
  $enregistrement->def_support_activite($support);
  
  $trouve = $enregistrement->lire_identite();
 
  // --- Mise en forme des informations avant retour
  
  if (!$trouve) {
    $donnee = array('code' => $code, 'err' => 'enregistrement introuvable');
  } else {
    $enregistrement->modifier_actif($code, $statut);
    
    // Informations utiles pour identifier le support
    
    $donnee[] = $enregistrement->support_activite()->nom();
  
    // message indiquant le resultat de l'action
    if ($enregistrement->support_activite()->est_actif())
      $donnee[] = "est maintenant actif, c'est-à-dire qu'il est utilisable dans Resabel.";
    else
      $donnee[] = "n'est maintenant plus actif, c'est à dire que l'on ne peut plus l'utiliser dans Resabel";
  }
  
  // --- Reponse a la requete
  
  $resultat_json = json_encode($donnee);
  echo $resultat_json;
  exit();
  
  // ==========================================================================
?>
