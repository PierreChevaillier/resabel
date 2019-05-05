<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : traitement requete / information personne (json)
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - pour traitement requete ajax
  // dependances : script qui lance la requete : requete_info_personne.js
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 27-avr-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // -
  // a faire :
  // - afficher les roles de la personne (dans les composantes du club)
  // ==========================================================================
  
  set_include_path('./../../');
  
  include('php/utilitaires/controle_session.php');
  
  // --- connection a la base de donnees (et instantiation du 'handler')
  include_once 'php/bdd/base_donnees.php';
  
  // --- classes utilisees
  require_once 'php/metier/membre.php';
  require_once 'php/bdd/enregistrement_membre.php';
  require_once 'php/elements_page/specifiques/vue_personne.php';
  
  require_once 'php/metier/commune.php';
  require_once 'php/bdd/enregistrement_commune.php';
  // --------------------------------------------------------------------------
  
  // --- informations fournies dans la requete
  //     Le code de la personne
  // on recoit :
  if (isset($_GET['code']))
    $code = $_GET['code'];
  else
    $code = 0;
    
  // --- Recherche des informations sur la personne correspondante
  //     dans la base de donnees
    
  $personne = new Membre($code);
  $enreg_membre = new Enregistrement_Membre();
  $enreg_membre->def_membre($personne);
    
  $trouve = false;
  $trouve = $enreg_membre->lire();
  
  // --- Mise en forme des informations avant retour
  
  if (!$trouve) {
      $donnee = array('code' => $code, 'err' => 'membre introuvable');
  } else {
    
    // Informations utiles pour identifier la personne
    $civil = $personne->civilite();
    $donnee[] = $civil . " " . $personne->prenom . " " . $personne->nom;
    //$presentation_nom = new Afficheur_Nom();
    //$presentation_nom->def_personne($personne);
    //$prenom_nom = $presentation_nom->formatter();
    //$donnee[] = $civil . " ". $prenom_nom;
    
    // Informations utiles pour contacter la personne
    
    $presentation_tel = new Afficheur_Telephone();
    if (strlen($personne->telephone) > 0)
      $donnee[] = $presentation_tel->formatter($personne->telephone);
    if (strlen($personne->telephone2) > 0)
      $donnee[] = $presentation_tel->formatter($personne->telephone2);
    
    if (strlen($personne->courriel) > 0) {
      $presentation_courriel = new Afficheur_Courriel_Actif();
      $presentation_courriel->def_personne($personne);
      $donnee[] = $presentation_courriel->formatter("Je te contacte pour ",  "");
    }
    
    if (strlen($personne->rue) > 0)
      $donnee[] = $personne->rue;
    
    $commune = new Commune();
    $commune->def_code($personne->code_commune);
    $enreg_commune = new Enregistrement_Commune();
    $enreg_commune->def_commune($commune);
    if ($enreg_commune->lire()) {
      $donnee[] = $commune->code_postal() . " " . $commune->nom();
    }
    
    $licence = $personne->num_licence;
    if (strlen($licence))
      $donnee[] = "Licence : " . $licence;
    // Informations utiles pour l'activite
    
    if ($personne->est_chef_de_bord())
      $donnee[] = 'chef' . (($personne->genre == "F")? 'fe' : '') . ' de bord';
    
    if ($personne->est_debutant())
      $donnee[] = 'débutant' . (($personne->genre == "F")? 'e' : '');
  }
  
  // --- Reponse a la requete
  
  $resultat_json = json_encode($donnee);
  echo $resultat_json;
  exit();
  
  // ==========================================================================
?>
