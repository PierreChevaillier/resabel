<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : traitement requete (json) verification ide,ntifiant membre
  // copyright (c) 2018-2023 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - pour traitement requete ajax
  // dependances : script qui lance cette requete
  // utilise avec :
  //    - PHP 8.2 sur macOS 13.2 ;
  // --------------------------------------------------------------------------
  // creation : 23-feb-2023 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
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

$code_membre = 0;
$identifiant = "";

$code_membre = (isset($_GET['code'])) ? $_GET['code'] : 0;

if (isset($_GET['id'])) // && preg_match('/[01]/', $_GET['cdb']))
  $identifiant = $_GET['id'];
//else
//  die();

// --- Recherche des informations dans la base de donnees
  
$personne = new Membre($code_membre);
$enreg_membre = new Enregistrement_Membre();
$enreg_membre->def_membre($personne);

$condition = $enreg_membre->verifier_identifiant_unique($identifiant);

// --- Mise en forme des informations avant retour

if ($condition)
  $donnee = array('status' => 1);
else
  $donnee = array('status' => 0);

// --- Reponse a la requete
$resultat_json = json_encode($donnee);
echo $resultat_json;
exit();
  
// ==========================================================================
?>
