<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : traitement requete / affichage info sur support activite (json)
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
  
  // --- Recherche des informations sur le support d'activite
  //     dans la base de donnees
    
  $enregistrement = new Enregistrement_Support_Activite();
  $trouve = $enregistrement->lire($code);
 
  // --- Mise en forme des informations avant retour
  
  if (!$trouve) {
    $donnee = array('code' => $code, 'err' => 'enregistrement introuvable');
  } else {
    $support = $enregistrement->support_activite();
    // Informations utiles pour identifier le support
    
    $donnee[] = $support->nom();
    if (is_a($support, 'Bateau')) {
      $donnee[] = "Numéro " . $support->numero();
      $donnee[] = "Bateau de type " . $support->nom_type();
    } else {
      $donnee[] = $support->nom_type();
    }
    
    if (!$support->est_actif()) {
      $donnee[] = "Actuellement non disponible dans Resabel";
    } else {
      $donnee[] = "Nombre de place(s) : " . $support->capacite();
      
      if ($support->est_pour_competition())
        $donnee[] = "Utilisation en compétition";
      if ($support->est_pour_loisir())
        $donnee[] = "Utilisation pour pratique loisir";
    }
  }
  
  // --- Reponse a la requete
  
  $resultat_json = json_encode($donnee);
  echo $resultat_json;
  exit();
  
  // ==========================================================================
?>
