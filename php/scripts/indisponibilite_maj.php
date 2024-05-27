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
 * - formulaire
 *   - formulaire_indisponibilite.php pour creation et modification
 * utilise avec :
 * - depuis 2023 :
 *   PHP 8.2 sur macOS 13.x
 *   PHP 8.1 sur hebergeur web
 * ----------------------------------------------------------------------------
 * creation : 03-mai-2024 pchevaillier@gmail.com
 * revision : 21-mai-2024 pchevaillier@gmail.com + creation et modification
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
require_once 'php/metier/indisponibilite.php';
require_once 'php/metier/calendrier.php';

// ----------------------------------------------------------------------------
// Champs de la requete
  
$action = (isset($_GET['act']))? $_GET['act']: '';
$code_indispo = (isset($_GET['id']))? intval($_GET['id']): -1;
$type_indispo = (isset($_GET['typ']))? intval($_GET['typ']): -1;

$status = 0;
if ((strlen($action) == 0) || ($code_indispo == -1) || ($type_indispo == -1)) {
  $status = 2;
  $donnees = array('status' => $status);
  $resultat_json = json_encode($donnees);
  echo $resultat_json;
  exit();
} else {
  $status = 1;
}

$url_retour = '';

// ----------------------------------------------------------------------------
// instantiation d'une indisponibilite du bon type
$indispo = null;
$code_objet = 0;
if ($type_indispo == Enregistrement_Indisponibilite::CODE_TYPE_INDISPO_SUPPORT) {
  $indispo = new Indisponibilite_Support($code_indispo);
  $code_objet = $_POST['support'];
  $indispo->def_support(new Support_Activite($code_objet));
  $url_retour = 'indisponibilites.php';
} elseif ($type_indispo == Enregistrement_Indisponibilite::CODE_TYPE_INDISPO_SITE) {
  $indispo = new Fermeture_Site($code_indispo);
  $code_objet = $_POST['site'];
  $indispo->def_site_activite(new Site_Activite($code_objet));
  $url_retour = 'fermetures_sites.php';
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
} elseif ($action[0] == 'c' || $action[0] == 'm') {
  // creation ou modification d'une indispo
  // a partir des donnees saisies
  // dans le formulaire (methode POST)
  $ok = true;
  // periode
  $debut = new Instant($_POST['date_deb'] . ' ' . $_POST['hre_deb']);
  $fin = new Instant($_POST['date_fin'] . ' ' . $_POST['hre_fin']);
  $indispo->definir_periode($debut, $fin);
  // createurice
  
  // motif
  $code = $_POST['motif'];
  $indispo->def_motif(new Motif_Indisponibilite($code));
  $info = $_POST['info'];
  $indispo->def_information($info);
  

  if ($action[0] == 'c') {
    if (isset($_SESSION['usr']))
        $indispo->def_createurice(new Membre($_SESSION['usr']));
    if ($type_indispo == Enregistrement_Indisponibilite::CODE_TYPE_INDISPO_SUPPORT) {
      $enregistrement->ajouter_indisponibilite_support();
    } elseif ($type_indispo == Enregistrement_Indisponibilite::CODE_TYPE_INDISPO_SITE) {
      $enregistrement->ajouter_fermeture_site();
    }
  } else {
    $ok = $enregistrement->modifier();
  }
  header('location:../../' . $url_retour);
  exit();
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
