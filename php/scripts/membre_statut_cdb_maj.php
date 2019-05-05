<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : traitement requete / information personne (json)
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - pour traitement requete ajax
  // dependances : script qui lance cette requete : requete_maj_status_cdb.js
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 01-mai-2019 pchevaillier@gmail.com
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
  require_once 'php/metier/membre.php';
  require_once 'php/bdd/enregistrement_membre.php';
  // --------------------------------------------------------------------------
  
  // --- informations fournies dans la requete
  //     Le code de la personne
  // on recoit :
  $code = (isset($_GET['code'])) ? $_GET['code'] : 0;

  if (isset($_GET['cdb']) && preg_match('/[01]/', $_GET['cdb']))
    $cdb = $_GET['cdb'];
  else
    die();
  
  // --- Recherche des informations sur la personne correspondante
  //     dans la base de donnees
    
  $personne = new Membre($code);
  $enreg_membre = new Enregistrement_Membre();
  $enreg_membre->def_membre($personne);
  
  $trouve = $enreg_membre->lire();
  
  // --- Mise en forme des informations avant retour
  
  if (!$trouve) {
      $donnee = array('code' => $code, 'err' => 'membre introuvable');
  } else {
    $enreg_membre->modifier_cdb($cdb);
    
    // Informations utiles pour identifier la personne
    $civil = $personne->civilite();
    $donnee[] = $civil . " " . $personne->prenom . " " . $personne->nom;
    
    if ($personne->est_chef_de_bord())
      $donnee[] = "est maintenant chef" . (($personne->genre == "F")? "fe" : "") . " de bord";
    else
      $donnee[] = "n'est maintenant plus chef" . (($personne->genre == "F")? "fe" : "") . " de bord";
  }
  
  // --- Reponse a la requete
  
  $resultat_json = json_encode($donnee);
  echo $resultat_json;
  exit();
  
  // ==========================================================================
?>
