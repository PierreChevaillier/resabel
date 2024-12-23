<?php
/* ============================================================================
 * Resabel - systeme de REServAtion de Bateau En Ligne
 * Copyright (C) 2024 Pierre Chevaillier
 * ----------------------------------------------------------------------------
 * description : traitement requete (json) pour la mise a jour des informations
 *               relatives a la participation a une seance d'actvite :
 *               inscription, desinscription, changement horaire ou support...
 * ----------------------------------------------------------------------------
 * utilisation : php -  XMLHttpRequest (javascript)
 * dependances : javascript qui lance cette requete ($_GET)
 * utilise avec :
 * - depuis 2023 :
 *   PHP 8.2 sur macOS 13.x
 * PHP 8.1 sur hebergeur web
 * ----------------------------------------------------------------------------
 * creation : 08-feb-2020 pchevaillier@gmail.com
 * revision : 17-feb-2023 pchevaillier@gmail.com + changement horaire
 * revision : 25-jan-2024 pchevaillier@gmail.com + changement support
 * revision : 21-aug-2024 pchevaillier@gmail.com + seance accueil sans resp et source avec
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * -
 * a faire :
 * - TODO: utiliser Enregistrement_Site_Activite::creer
 * ============================================================================
*/
set_include_path('./../../');
  
include('php/utilitaires/controle_session.php');
  
// --- connection a la base de donnees (et instantiation du 'handler')
//     utilise dans les operations de Enregistrement_Seance_Activite
include_once 'php/bdd/base_donnees.php';
  
  // --- classes utilisees
require_once 'php/bdd/enregistrement_site_activite.php';
require_once 'php/bdd/enregistrement_seance_activite.php';
require_once 'php/bdd/enregistrement_support_activite.php';

  // --------------------------------------------------------------------------
  
  // Champs json de la requete
  
  $action = (isset($_GET['act']))? $_GET['act']: '';
  
  $info_participation = new Information_Participation_Seance_Activite();
  
  $info_participation->code_seance = (isset($_GET['id']))? intval($_GET['id']): 0;
  $info_participation->code_site = (isset($_GET['sa']))? intval($_GET['sa']): 0;
  $info_participation->code_support_activite = (isset($_GET['s']))? intval($_GET['s']): 0;
  $info_participation->debut = (isset($_GET['deb']))? $_GET['deb']: '';
  $info_participation->fin = (isset($_GET['fin']))? $_GET['fin']: '';
  $info_participation->code_participant = (isset($_GET['p']))? intval($_GET['p']): 0;
  $info_participation->responsable = (isset($_GET['resp']))? intval($_GET['resp']): 0;
  
  $status = 0;
  //$erreur = false;
  //$donnees = array('res' => $status);
  
  // Verification coherence des informations recues
// TODO: en tenir compte dans la suite du programme
$seance_existe = false;
if ($info_participation->code_seance > 0) {
  $seance_existe = Enregistrement_Seance_Activite::seance_existe($info_participation->code_seance);
/*
 if (!$seance_existe) {
    $donnees = array('status' => 0);
    $resultat_json = json_encode($donnees);
    echo $resultat_json;
    exit();
  }
 */
}

  // --------------------------------------------------------------------------
  // realisation de l'action (selon le type d'action)
  $ok = false;

  if ($action[0] == 'i') { // ii (inscr. individuelle) ou ie (inscr. equipage)
    $status = Enregistrement_Seance_Activite::ajouter_participation($info_participation);
    $ok = ($status == 1);
    if ($status != 1) {
      $donnees = array('status' => $status);
      $resultat_json = json_encode($donnees);
      echo $resultat_json;
      exit();
    }
  } elseif ($action == 'di') {
    $status = Enregistrement_Seance_Activite::supprimer_participation($info_participation);
    $ok = ($status == 1);
  } elseif ($action == 'de') {
    $ok = Enregistrement_Seance_Activite::supprimer_seance($info_participation->code_seance);
  } elseif ($action == 'mre') {
    $ok = Enregistrement_Seance_Activite::passer_responsable_equipier($info_participation->code_seance);
  } elseif ($action == 'mer') {
    $ok = Enregistrement_Seance_Activite::passer_equipier_responsable($info_participation->code_seance,
                                                                      $info_participation->code_participant
                                                                      );
  } elseif ($action == 'mc') {
    $ok = Enregistrement_Seance_Activite::changer_horaire($info_participation->code_seance,
                                                          $info_participation->debut,
                                                          $info_participation->fin
                                                          );
  } elseif ($action == 'msa') {
    // Ce qu'il faut faire differe selon que l'on fusionne 2 seances
    // ou que l'on change juste le support de la seance
    
    // --- le site d'activite
    $site = Enregistrement_Site_Activite::creer($info_participation->code_site);
    $ok = !is_null($site);
    
    // (1) obtenir les informations sur la seance
    $seance = Enregistrement_Seance_Activite::creer($info_participation->code_seance);
    $ok = !is_null($seance);
    
    if ($ok) {
      $seance_accueil = null;
      // (2) rechercher l'eventuelle seance sur le support d'accueil et au meme horaire
      $seances = array();
      $debut = $seance->debut()->date_heure_sql();
      $fin = $seance->fin()->date_heure_sql();
      $support_accueil = $info_participation->code_support_activite;
      $criteres = 'seance.code_support = ' . $support_accueil
        . ' AND seance.date_debut = \'' . $debut . '\''
        . ' AND seance.date_fin = \'' . $fin . '\''
      . ' ';
      Enregistrement_Seance_Activite::collecter($site, $criteres, "", $seances);
      $fusion = (count($seances) == 1);
      
      if ($fusion) {
        // cas ou on fusionne les participations
        foreach ($seances as $s)
          $seance_accueil = $s;
        // les participations de la seance passent sur la seance d'accueil
        $ok = Enregistrement_Seance_Activite::changer_seance($seance->code(),
                                                              $seance_accueil->code()
                                                              );
        // si la seance d'accueil n'avait pas de responsable
        // et que le seance source en avait un,
        // alors ce dernier devient le responsable de la seance d'accueil.
        $supports = array();
        $criteres = "support.code = " . $support_accueil;
        Enregistrement_Support_Activite::collecter($criteres, "", $supports);
        foreach ($supports as $sa)
          $seance_accueil->def_support($sa);
        if ($seance_accueil->responsable_requis()) {
          if ((!$seance_accueil->a_un_responsable()) && $seance->a_un_responsable()) {
            $code_resp = $seance->responsable->code();
            $ok = Enregistrement_Seance_Activite::passer_equipier_responsable($seance_accueil->code(),
                                                                              $code_resp);
          }
        }
        
        // puis on supprime la seance qui est alors vide de participation
        // il faut le faire *apres* le changement de seance
        // car cette derniere supprime les eventuelles participations
        $ok = $ok && Enregistrement_Seance_Activite::supprimer_seance($seance->code());

      } else {
        // pas de seance d'accueil donc on change juste le support de la seance
        $ok = Enregistrement_Seance_Activite::changer_support($info_participation->code_seance,
                                                              $info_participation->code_support_activite
                                                              );
      }
    }
  }
  // --------------------------------------------------------------------------
  // Reponse a la requete :

  $status = $ok ? 1 : 0;
  $donnees = array('status' => $status);
  //array('s' => $supports_json, 'ts' => $types_support_json, 'pc' => $creneaux_json, 'dc' => $creneaux_json);

  $resultat_json = json_encode($donnees);
  echo $resultat_json;
  exit();
  // ==========================================================================
?>
