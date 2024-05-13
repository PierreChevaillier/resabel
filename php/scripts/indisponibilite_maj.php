<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 * description : traitement requete pour la mise a jour des informations
 *               relatives a une indisponibilite
 * copyright (c) 2018-2024 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : javascript (XMLHttpRequest - GET)
 * dependances :
 * - script qui envoie la requete
 *   - actions_indisponibilite.js pour la suppression
 * utilise avec :
 * - depuis 2023 :
 *   PHP 8.2 sur macOS 13.x
 *   PHP 8.1 sur hebergeur web
 * ----------------------------------------------------------------------------
 * creation : 03-mai-2024 pchevaillier@gmail.com
 * revision :
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * -
 * a faire :
 * -
 * ============================================================================
 */
set_include_path('./../../');

include 'php/utilitaires/controle_session.php';
  
// --- connection a la base de donnees (et instantiation du 'handler')
//     utilise dans les operations de Enregistrement_Indisponibilite
include_once 'php/bdd/base_donnees.php';
  
// --- classes utilisees
require_once 'php/bdd/enregistrement_indisponibilite.php';
  
// ----------------------------------------------------------------------------
// Champs de la requete
  
$action = (isset($_GET['act']))? $_GET['act']: '';
$code_indispo = (isset($_GET['id']))? intval($_GET['id']): 0;
$type_indispo = (isset($_GET['typ']))? intval($_GET['typ']): 0;

$status = 0;
if ((strlen($action) == 0) || ($code_indispo == 0) || ($type_indispo == 0)) {
  $status = 2;
  $donnees = array('status' => $status);
  $resultat_json = json_encode($donnees);
  echo $resultat_json;
  exit();
} else {
  $status = 1;
}

// ----------------------------------------------------------------------------
// instantiation d'une indisponibilite du bon type
$indispo = null;
if ($type_indispo == Enregistrement_Indisponibilite::CODE_TYPE_INDISPO_SUPPORT) {
  $indispo = new Indisponibilite_Support($code_indispo);
} elseif ($type_indispo == Enregistrement_Indisponibilite::CODE_TYPE_INDISPO_SITE) {
  $indispo = new Fermeture_Site($code_indispo);
} else {
  $donnees = array('status' => 9);
  $resultat_json = json_encode($donnees);
  echo $resultat_json;
  exit();
}

$enregistrement = new Enregistrement_Indisponibilite();
$enregistrement->def_indisponibilite($indispo);
// ----------------------------------------------------------------------------
// realisation de l'action (selon le type d'action)
$ok = false;

if ($action[0] == 's') {
  $ok = $enregistrement->supprimer();
}
// ----------------------------------------------------------------------------
// Reponse a la requete :

$status = $ok ? 1 : 0;
$donnees = array('status' => $status);
$resultat_json = json_encode($donnees);
echo $resultat_json;
exit();

// ============================================================================
?>
