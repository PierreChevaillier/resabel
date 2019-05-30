<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : traitement requete mise a jour niveau personne (json)
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - pour traitement requete ajax
  // dependances : script qui lance cette requete : requete_maj_niveau.js
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 05-mai-2019 pchevaillier@gmail.com
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

  if (isset($_GET['niv']) && preg_match('/[1-9][0-9]*/', $_GET['niv']))
    $niveau = $_GET['niv'];
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
    $donnee = array();
    $enreg_membre->modifier_niveau($niveau);
    
    // Informations utiles pour identifier la personne
    $civil = $personne->civilite();
    $donnee[] = $civil . " " . $personne->prenom . " " . $personne->nom;
    
    if ($personne->est_debutant())
      $donnee[] = "est maintenant repassé" . (($personne->genre == "F")? "e" : "") . " débutant" . (($personne->genre == "F")? "e" : "");
    else
      $donnee[] = "n'est maintenant plus débutant" . (($personne->genre == "F")? "e" : "");
  }
  
  // --- Reponse a la requete
  
  $resultat_json = json_encode($donnee);
  echo $resultat_json;
  exit();
  
  // ==========================================================================
?>
