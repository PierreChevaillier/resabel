<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 * description : traitement requete (json) : recherche support disponibles
 *               en vue du changement de support pour une activite
 * copyright (c) 2018-2024 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : php - traitement XMLHttpRequest (javascript)
 * dependances :
 * - javascript qui lance cette requete ($_GET)
 * utilise avec :
 * - depuis 2023 :
 *   PHP 8.2 sur macOS 13.x
 *   PHP 8.1 sur hebergeur web
 * ----------------------------------------------------------------------------
 * creation : 18-dec-2023 pchevaillier@gmail.com
 * revision : 24-jan-2024 pchevaillier@gmail.com
 * ----------------------------------------------------------------------------
 * commentaires :
 * - il ya deux requetes sur la table des activites => fusionner en une seule
 * attention :
 * - en chantier
 * a faire :
 * -
 * ============================================================================
 */
declare(strict_types=1);
 
set_include_path('./../../');

include('php/utilitaires/controle_session.php');

// --- connection a la base de donnees (et instantiation du 'handler')
//     utilisee dans les operations de Enregistrement_XXX
include_once 'php/bdd/base_donnees.php';

// --- classes utilisees
require_once 'php/metier/site_activite.php';

require_once('php/bdd/enregistrement_site_activite.php');
require_once 'php/bdd/enregistrement_seance_activite.php';
require_once('php/bdd/enregistrement_support_activite.php');
require_once('php/bdd/enregistrement_indisponibilite.php');
// ============================================================================

//$action = (isset($_GET['act']))? $_GET['act']: '';

$code_seance = (isset($_GET['id']))? intval($_GET['id']): 0;
$code_site = (isset($_GET['sa']))? intval($_GET['sa']): 1;

//$info_participation = new Information_Participation_Seance_Activite();
//$info_participation->code_seance = (isset($_GET['id']))? intval($_GET['id']): 0;

$ok = ($code_seance != 0);
$status = -1;

// ----------------------------------------------------------------------------
// --- le site d'activite
$site = null;

$sites = array();
$criteres = 'site.code = ' . $code_site;
Enregistrement_Site_Activite::collecter($criteres, "", $sites);
foreach ($sites as $sa)
  $site = $sa;
$ok = !is_null($site) && $ok;

// --- Obtenir la seance et ses participations
$seance = null;
$seances = array();
$criteres = ' seance.code = ' . $code_seance . ' ';
Enregistrement_Seance_Activite::collecter($site, $criteres, "", $seances);
foreach ($seances as $s)
  $seance = $s;
$ok = !is_null($seance) && $ok;

// --- Les supports actifs sur le site
$supports = array();
$criteres = "support.code_site_base = " .  $site->code()
  . " AND support.actif = 1 ";
Enregistrement_Support_Activite::collecter($criteres, " type DESC, code ASC", $supports);

// associer les informations completes sur le support a la seance
$seance->def_support($supports[$seance->code_support()]);

// --- Les indisponibites de support couvrant le creneau horaire de l'activite
$criteres = " date_debut <= '" . $seance->fin()->date_heure_sql()
  . "' AND  date_fin >= '" . $seance->debut()->date_heure_sql() . "'";
  
$indispos = array();
$type_indispo = 1;
Enregistrement_Indisponibilite::collecter($site, $type_indispo, $criteres, "", $indispos);
$status = count($indispos);
foreach ($indispos as $indispo) {
  $i = $indispo->support->code();
  unset($supports[$i]);
}

// --- Les seances d'activites sur le creneau horaire

$seances = array();
$critere_selection = "code_site = " . $site->code()
  . " AND date_debut <= '" . $seance->fin()->date_heure_sql()
  . "' AND date_fin >= '" . $seance->debut()->date_heure_sql() . "'";
$critere_tri = ""; // code_support ASC ";

Enregistrement_Seance_Activite::collecter($site,
                                          $critere_selection,
                                          $critere_tri,
                                          $seances);
// il faut enlever les supports utilises par des activites ne pouvant accueillir
// l'equipage actuel
foreach ($seances as $s) {
    if (! $seance->peut_accueillir_participants($s)) {
      $i = $s->code_support();
      unset($supports[$i]);
    }
}

if (!$ok) {
  // Une erreur est survenue
  $donnees = array('err' => $status);
  $resultat_json = json_encode($donnees);
  echo $resultat_json;
  exit();
}

// Creation du tableau des supports possibles
$possibles = array();
foreach ($supports as $support) {
  $x = array();
  $x['value'] = $support->numero();
  $x['places'] = 'XX places';
  $possibles[$support->code()] = json_encode($x);
}

// ----------------------------------------------------------------------------
// Reponse a la requete :
 
//$donnees = array('res' = $status);

$resultat_json = json_encode($possibles);
echo $resultat_json;
exit();

// ============================================================================
?>

