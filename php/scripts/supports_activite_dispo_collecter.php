<?php
/* ============================================================================
 * Resabel - systeme de REServAtion de Bateau En Ligne
 * Copyright (C) 2024 Pierre Chevaillier
 * ----------------------------------------------------------------------------
 * description : traitement requete (json) : recherche supports disponibles
 *               en vue du changement de support pour une activite
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
 * revision : 23-fev-2024 pchevaillier@gmail.com cas supports pas utilises
 * revision : 16-jul-2024 pchevaillier@gmail.com + retour erreur
 * revision : 21-aug-2024 pchevaillier@gmail.com + exlut support de la seance 'source'
 * revision : 12-sep-2024 pchevaillier@gmail.com bug fix: * supports non utilises
 * ----------------------------------------------------------------------------
 * commentaires :
 * - il y a deux requetes sur la table des activites
 *   => envisager de les fusionner en une seule
 * attention :
 * -
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

function retourne_erreur(int $code_erreur): void {
  $erreurs = array();
  $err = array();
  $err['value'] = $code_erreur;
  $x['places'] = 'dummy';
  $erreurs[0] = json_encode($err);
  $resultat_json = json_encode($erreurs);
  echo $resultat_json;
  exit();
}

// ============================================================================

$code_seance = (isset($_GET['id']))? intval($_GET['id']): 0;
$code_site = (isset($_GET['sa']))? intval($_GET['sa']): 1;

$status = 1;
$ok = ($code_seance != 0);
if (!$ok) retourne_erreur(1);

// ----------------------------------------------------------------------------
// --- le site d'activite
$site = null;

$sites = array();
$criteres = 'site.code = ' . $code_site;
Enregistrement_Site_Activite::collecter($criteres, "", $sites);
foreach ($sites as $sa)
  $site = $sa;
$ok = !is_null($site);
if (!$ok) retourne_erreur(2);


// --- Obtenir la seance et ses participations
$seance = null;
$seances = array();
$criteres = ' seance.code = ' . $code_seance . ' ';
Enregistrement_Seance_Activite::collecter($site, $criteres, "", $seances);
foreach ($seances as $s)
  $seance = $s;
$ok = !is_null($seance);
if (!$ok) retourne_erreur(3);

// --- Les supports actifs sur le site
$supports = array();
$criteres = "support.code_site_base = " .  $site->code()
  . " AND support.actif = 1 ";
Enregistrement_Support_Activite::collecter($criteres, " support.code_type_support DESC, support.code ASC", $supports);

// associer les informations completes sur le support a la seance
$cle = $seance->code_support();
$ok = array_key_exists($cle, $supports);
if (!$ok) retourne_erreur(4);
$seance->def_support($supports[$cle]);

// --- Les indisponibites de support couvrant le creneau horaire de l'activite
$criteres = " date_debut < '" . $seance->fin()->date_heure_sql()
  . "' AND  date_fin > '" . $seance->debut()->date_heure_sql() . "'";
  
$indispos = array();
$type_indispo = 1;
$ok = Enregistrement_Indisponibilite::collecter($site, $type_indispo, $criteres, "", $indispos);
if (!$ok) retourne_erreur(5);
$status = count($indispos);
foreach ($indispos as $indispo) {
  $cle = $indispo->support->code();
  unset($supports[$cle]);
}

// --- Enlever le support de la seance actuelle
$cle = $seance->code_support();
unset($supports[$cle]);

// --- Les seances d'activites sur le creneau horaire

$seances = array();
$critere_selection = "code_site = " . $site->code()
  . " AND date_debut < '" . $seance->fin()->date_heure_sql()
  . "' AND date_fin > '" . $seance->debut()->date_heure_sql() . "'";
$critere_tri = ""; // code_support ASC ";

$ok = Enregistrement_Seance_Activite::collecter($site,
                                          $critere_selection,
                                          $critere_tri,
                                          $seances);
if (!$ok) retourne_erreur(6);

// il faut enlever les supports utilises par des activites ne pouvant accueillir
// l'equipage actuel
foreach ($seances as $s) {
  if ($s->code() != $seance->code()) {
    // associer les informations completes sur le support a la seance
    $s->def_support($supports[$s->code_support()]);
    if (! $s->peut_accueillir_participants($seance)) {
      $cle = $s->code_support();
      unset($supports[$cle]);
    }
  }
}

// il faut aussi enlever les supports ne pouvant pas accueillir l'equipage
// et pas affectes a une seance
$supports_utilises = array();
foreach ($seances as $s) {
  $support_utilises[$s->code_support()] = $s->support;
}
/*
foreach ($supports as $support) {
  $code_support = $support->code();
  if (!array_key_exists($code_support, $support_utilises)) {
    $s = new Seance_Activite();
    $s->def_support($support);
    if (! $s->peut_accueillir_participants($seance)) {
      unset($supports[$code_support]);
    }
  }
}
 */

$codes_support = array_keys($supports);
foreach ($codes_support as $code_support) {
  if (!array_key_exists($code_support, $support_utilises)) {
    $s = new Seance_Activite();
    $s->def_support($supports[$code_support]);
    if (! $s->peut_accueillir_participants($seance)) {
      unset($supports[$code_support]);
    }
  }
}


// ----------------------------------------------------------------------------
// Reponse a la requete :

// Creation du tableau des supports possibles
$possibles = array();
foreach ($supports as $support) {
  $x = array();
  $x['value'] = $support->numero();
  $x['places'] = 'XX places';
  $possibles[$support->code()] = json_encode($x);
}

$resultat_json = json_encode($possibles);
echo $resultat_json;
exit();

// ============================================================================
?>

