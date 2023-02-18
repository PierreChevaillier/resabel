<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : traitement requete (json) pour la mise a jour des informations
  //               relatives a la participation a une seance d'actvite :
  //               inscription, desinscription...
  // copyright (c) 2018-2023 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - pour traitement requete ajax
  // dependances : javascript qui lance cette requete ($_GET)
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //  - depuis jan-2023 :
  //              PHP 8.2 sur macOS 13.2
  // --------------------------------------------------------------------------
  // creation : 08-feb-2020 pchevaillier@gmail.com
  // revision : 20-apr-2020 pchevaillier@gmail.com
  // revision : 17-feb-2023 pchevaillier@gmail.com + changement horaire
  // --------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // ==========================================================================
  set_include_path('./../../');
  
  include('php/utilitaires/controle_session.php');
  
  // --- connection a la base de donnees (et instantiation du 'handler')
  //     utilise dans les operations de Enregistrement_Seance_Activite
  include_once 'php/bdd/base_donnees.php';
  
  // --- classes utilisees
  require_once 'php/bdd/enregistrement_seance_activite.php';
  
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
  $erreur = false;
  //$donnees = array('res' => $status);
  
  // Verification coherence des informations recues
  /*
  if ($erreur) {
    $resultat_json = json_encode($donnees);
    echo $resultat_json;
    exit();
  }
  */
  // --------------------------------------------------------------------------
  // realisation de l'action (selon le type d'action)
  
  if ($action[0] == 'i') { // ii (inscr. individuelle) ou ie (inscr. equipage)
    $status = Enregistrement_Seance_Activite::ajouter_participation($info_participation);
  } elseif ($action == 'di') {
    $status = Enregistrement_Seance_Activite::supprimer_participation($info_participation);
  } elseif ($action == 'de') {
    $status = Enregistrement_Seance_Activite::supprimer_seance($info_participation->code_seance);
  } elseif ($action == 'mre') {
    $status = Enregistrement_Seance_Activite::passer_responsable_equipier($info_participation->code_seance);
  } elseif ($action == 'mer') {
    $status = Enregistrement_Seance_Activite::passer_equipier_responsable($info_participation->code_seance,
                                                                          $info_participation->code_participant
                                                                          );
  } elseif ($action == 'mc') {
    $status = Enregistrement_Seance_Activite::changer_horaire($info_participation->code_seance,
                                                              $info_participation->debut,
                                                              $info_participation->fin
                                                              );
  }
  // --------------------------------------------------------------------------
  // Reponse a la requete :
   
  $donnees = array('res' => $status);
  //array('s' => $supports_json, 'ts' => $types_support_json, 'pc' => $creneaux_json, 'dc' => $creneaux_json);

  $resultat_json = json_encode($donnees);
  echo $resultat_json;
  exit();
  // ==========================================================================
?>
