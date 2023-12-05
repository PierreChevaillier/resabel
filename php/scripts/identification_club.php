<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 * description :  verifie les informations de connexion en tant que Club
 *              - donc sans utlisateur indetifie de maniere personnelle
 * copyright (c) 2014-2023 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : action suite soumission formulaire connexion club
 * dependances :
 * - variables transmises depuis le formulaire html/javascript
 * - variable $_SESSION
 * utilise avec :
 * - depuis 2023 :
 *   PHP 8.2 sur macOS 13.x
 *   PHP 8.1 sur hebergeur web
 * ----------------------------------------------------------------------------
 * creation : 03-dec-2023 pchevaillier@gmail.com
 * revision :
 * ----------------------------------------------------------------------------
 * commentaires :
 * - les informations de connexion sont stockees dans $_SESSION
 * attention :
 * -
 * a faire :
 * -
 * ============================================================================
*/

session_start(); // doit etre la premiere instruction

// ============================================================================
// --- classes utilisees
set_include_path('./../../');

include 'php/utilitaires/definir_locale.php';

// --- connection a la base de donnees
include 'php/bdd/base_donnees.php';

// --- Classes utlisees
require_once 'php/metier/calendrier.php';
require_once 'php/metier/club.php';
require_once 'php/bdd/enregistrement_club.php';
// ============================================================================

// Les variables suivantes sont utilisees a partir d'ici
// Variables toujours definies
unset($_SESSION['clb']);    // code du club
unset($_SESSION['id_clb']); // identifiant du club
unset($_SESSION['n_clb']);  // nom du club p.ex. AMP

unset($_SESSION['prs']); // set (=true) si session 'perso' unset : session 'club'

// les variables suivantes ne sont definies que si session 'perso'
unset($_SESSION['usr']);   // si session perso: code membre personne connectee
unset($_SESSION['n_usr']); // si session perso: code usage membre personne connectee

unset($_SESSION['act']); // personne connectee active (cf. classe Personne)
unset($_SESSION['cdb']); // personne connectee est chef de bord
unset($_SESSION['prm']); // personne connectee est de permanence
unset($_SESSION['adm']); // personne connectee est administatrice de resabel

if (isset($_GET['c'])) {
  $code_club = intval($_GET['c']);
  $_SESSION['clb'] = $code_club;
} else {
  header("location: ../../index.html");
  exit();
}

if (isset($_GET['s'])) {
  $code_site = intval($_GET['s']);
  $_SESSION['swb'] = $code_site;
} else {
  header("location: ../../index.html");
  exit();
}
  
// --- recuperation des informations saisies dans le formulaire
if (!isset($_POST['mdp'])) {
  header("location: ../../index.html");
  exit();
}
$mdp_clair = stripslashes(trim($_POST['mdp']));



// ============================================================================
// --- verification identification

$identification_ok = false;
$club = new Club($code_club);
$enreg_club = new Enregistrement_Club();
$enreg_club->def_club($club);
$club_existe = $enreg_club->lire();
if ($club_existe) {
  try {
    $identification_ok = $enreg_club->verifier_identite($mdp_clair);
  } catch (Erreur_Club_introuvable $e) {
    die("Erreur fatale - Club introuvable, code :" . $code_club);
  } catch (Erreur_Identifiant_Club $e) {
    die("Erreur fatale - identifiant club incoherent, code :" . $club->identifiant());
  } catch (Erreur_Mot_Passe_Club $e) {
    header("location: ../../connexion_club.php?err=mdp&c=" . $code_club . "&s=" . $code_site);
  }
}
// ============================================================================
// --- Affichage contextuel en fonction du resultat de l'identification
//     et initialisation des variables de session
if ($identification_ok) {
  $_SESSION['clb'] = $club->code();
  $_SESSION['id_clb'] = $club->sigle();
  $_SESSION['n_clb'] = $club->nom();
  
  $jour = Calendrier::aujourdhui();
  $url = '../../activites.php?a=l&j=' . $jour->valeur_cle_date();
  header('location: ' . $url);
}

exit();
// ============================================================================
?>
