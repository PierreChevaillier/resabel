<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 * description : Fichier vide -  modele entete
 * copyright (c) 2018-2024 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : php - appel dans javascript (XMLHttpRequest)
 * dependances :
 * - cles JSON ($_GET)
 * - input du formulaire (formulaire_saisie_equipage.php) : $_GET
 * utilise avec :
 * - depuis 2023 :
 *   PHP 8.2 sur macOS 13.x
 *   PHP 8.1 sur hebergeur web
 * ----------------------------------------------------------------------------
 * creation : 15-fev-2024 pchevaillier@gmail.com
 * revision :
 * ----------------------------------------------------------------------------
 * commentaires :
 * - 
 * attention :
 * -
 * a faire :
 * - verfication des donnees d'entree
 * - traiter le cas des erreurs de traitement (enregistrements en base de donnees)
 * ============================================================================
 */
set_include_path('./../../');

include('php/utilitaires/controle_session.php');
include('php/utilitaires/definir_locale.php');

// --- connection a la base de donnees (et instantiation du 'handler')
//     utilise dans les operations de Enregistrement_Seance_Activite
include_once 'php/bdd/base_donnees.php';

// --- classes utilisees
require_once 'php/bdd/enregistrement_seance_activite.php';

// ----------------------------------------------------------------------------
// --- traitement des donnees d'entree

$info_participation = new Information_Participation_Seance_Activite();

// --- Champs json de la requete
$info_participation->code_seance = (isset($_GET['id']))? intval($_GET['id']): -1;
$info_participation->code_site = (isset($_GET['sa']))? intval($_GET['sa']): -1;
$info_participation->code_support_activite = (isset($_GET['s']))? intval($_GET['s']): -1;
$info_participation->debut = (isset($_GET['deb']))? $_GET['deb']: 'undef';
$info_participation->fin = (isset($_GET['fin']))? $_GET['fin']: 'undef';
$code_resp = (isset($_GET['resp']))? intval($_GET['resp']): 0;

$participants = explode(',', $_GET['part']);

// --- Verification des donnees d'entree
$status = 0;
// -1 et undef
$ok = false;

// ----------------------------------------------------------------------------
// --- Requetes en base de donnees

// responsable seance (si on en a saisi un.e)
if ($code_resp > 0) {
  $info_participation->code_participant = $code_resp;
  $info_participation->responsable = 1;
  $status = Enregistrement_Seance_Activite::ajouter_participation($info_participation);
  $ok = ($status == 1);
}

// Eventuelle(s) autre(s) participation(s)
foreach ($participants as $p) {
  $info_participation->code_participant = $p;
  $info_participation->responsable = 0;
  $status = Enregistrement_Seance_Activite::ajouter_participation($info_participation);
  $ok = ($status == 1);
}
// ----------------------------------------------------------------------------
// --- Reponse a la requete

//$parts = json_encode($participants);

$donnees = array('status' => $status);
/*,
  'seance' => $info_participation->code_seance,
  'site' => $info_participation->code_site,
  'support' => $info_participation->code_support_activite,
  'debut' => $info_participation->debut,
  'fin' => $info_participation->fin,
  'resp' =>$info_participation->responsable,
  'part' => $parts
);
 */

$resultat_json = json_encode($donnees);
echo $resultat_json;
exit();
// ============================================================================
?>
