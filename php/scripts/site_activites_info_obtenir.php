<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : traitement requete (json) pour obtenir les informations
  //               necessaires pour le formualire de recherche de disponibilite
  //               de supports d'activite pour un site, a une date donnee
  // copyright (c) 2018-2020 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - pour traitement requete ajax
  // dependances : javascript qui lance cette requete
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.3 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 07-sep-2019 pchevaillier@gmail.com
  // revision : 05-jan-2020 pchevaillier@gmail.com
  // revision : 11-mar-2020 pchevaillier@gmail.com correction erreurs
  // --------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // ==========================================================================
  set_include_path('./../../');
  
  include('php/utilitaires/controle_session.php');
  include('php/utilitaires/definir_locale.php');
  
  // --- connection a la base de donnees (et instantiation du 'handler')
  include_once 'php/bdd/base_donnees.php';
  
  // --- classes utilisees
  require_once 'php/metier/site_activite.php';
  require_once 'php/bdd/enregistrement_site_activite.php';
  
  require_once 'php/metier/regime_ouverture.php';
  require_once 'php/bdd/enregistrement_regime_ouverture.php';
  
  require_once 'php/metier/calendrier.php';
  
  require_once 'php/metier/support_activite.php';
  require_once 'php/bdd/enregistrement_support_activite.php';
  // --------------------------------------------------------------------------
  
  // Champs json de la requete :
  $code_site = (isset($_GET['sa']))? $_GET['sa']: 0;
  $valeur_jour = (isset($_GET['j']))? $_GET['j']: calendrier::aujourdhui()->date_sql();
  $filtre_type_support = (isset($_GET['ts']))? $_GET['ts']: 0;
  
  // --- Recherche des supports d'activite
  $supports = null;
  Enregistrement_Support_Activite::collecter("code_site_base = " . $code_site . " AND actif = 1 ", " type DESC, code ASC", $supports);
  
  $choix_type_support = array();
  
  foreach ($supports as $support) {
    $choix_type_support[$support->type->code()] = $support->type->nom();
  }
  $types_support_json = json_encode($choix_type_support);
  $choix_support = array();
  
  foreach ($supports as $support) {
    if (($filtre_type_support == 0) || ($support->type->code() == $filtre_type_support))
      $choix_support[$support->code()] = $support->identite_texte();
  }
  $supports_json = json_encode($choix_support);
  
  // --- Recherche des creneaux d'activite pour le site selectionne
  $choix_creneaux = array();
  $site = Enregistrement_Site_Activite::creer($code_site);
  $regime_ouverture = Enregistrement_Regime_ouverture::creer($site->code_regime_ouverture());
  $date_ref = new Instant($valeur_jour);
  $creneaux = $regime_ouverture->definir_creneaux($date_ref, $site->latitude, $site->longitude);
  foreach ($creneaux as $creneau) {
     $choix_creneaux[$creneau->debut()->valeur_cle_horaire()] = $creneau->debut()->heure_texte();
  }
  $creneaux_json = json_encode($choix_creneaux);
   
  $donnees = array('s' => $supports_json, 'ts' => $types_support_json, 'pc' => $creneaux_json, 'dc' => $creneaux_json);
  
  // --- Reponse a la requete
  $resultat_json = json_encode($donnees);
  echo $resultat_json;
  exit();
  // ==========================================================================
?>
