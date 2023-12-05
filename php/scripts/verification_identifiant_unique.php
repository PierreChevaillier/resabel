<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : traitement requete (json) verification ide,ntifiant membre
  // copyright (c) 2018-2023 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - pour traitement requete ajax
  // dependances : script qui lance cette requete
  // utilise avec :
  //    - PHP 8.2 sur macOS 13.2 ;
  // --------------------------------------------------------------------------
  // creation : 23-feb-2023 pchevaillier@gmail.com
  // revision : 02-dec-2023 pchevaillier@gmail.com connexion et non plus membre
  // --------------------------------------------------------------------------
  // commentaires :
  //  - pourrat etre utilisase dans processus de changement de mot de passe.
  // attention :
  //  PAS utilise a ce stade.
  // a faire :
  // ==========================================================================
  
set_include_path('./../../');
  
include('php/utilitaires/controle_session.php');
  
// --- connection a la base de donnees (et instantiation du 'handler')
include_once 'php/bdd/base_donnees.php';

// --- classes utilisees
require_once 'php/metier/membre.php';
require_once 'php/bdd/enregistrement_connexion.php';
// --------------------------------------------------------------------------

// donnee qui sera retournee
$donnee = array();

// --- Controle des informations recues dans la requete
// - code de la personne
// - identifiant choisi a verifier
$code_membre = 0;
$identifiant = "";

$ok = true;
if (isset($_GET['code'])) { //}&& is_numeric($_GET['code'])) {
  $code_membre = intval($_GET['code']);
  $ok = ($code_membre > 0);
} else {
  $ok = false;
}
if ($ok) {
  if (isset($_GET['id']) && preg_match("#^[a-zA-Z0-9.-]+$#", $_GET['id'])) {
    $identifiant = stripslashes(trim($_GET['id']));
    $identifiant = strtolower($identifiant);
  } else {
    $ok = false;
  }
}
if (!$ok) {
  $donnee['status'] = 2;
  $resultat_json = json_encode($donnee);
  echo $resultat_json;
  exit();
}

// --- Recherche des informations de connexion dans la base de donnees
$cnx = new Connexion($code_membre);
$enreg_cnx = new Enregistrement_Connexion();
$enreg_cnx->def_connexion($cnx);
$condition = $enreg_cnx->verifier_identifiant_unique($identifiant);

// --- Mise en forme des informations avant reponse a la requete
//$donnee['code'] = $cnx->code_membre() //pour debug
//$donnee['id'] = $identifiant; // pour debug
if ($condition)
  $donnee['status'] = 1;
else
  $donnee['status'] = 0;

// --- Reponse a la requete
$resultat_json = json_encode($donnee);
echo $resultat_json;
exit();
  
// ==========================================================================
?>
