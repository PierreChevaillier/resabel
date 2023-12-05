<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 * description :  verifie les informations de connexion d'un utilisateur
 *              - identification de l'utilisateur pour la session
 * copyright (c) 2014-2023 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : action suite soumission formulaire connexion perso
 * dependances :
 * - variables transmises depuis le formulaire html/javascript
 * - variable $_SESSION
 * utilise avec :
 * - depuis 2023 :
 *   PHP 8.2 sur macOS 13.x
 *   PHP 8.1 sur hebergeur web
 * ----------------------------------------------------------------------------
 * creation : 12-nov-2023 pchevaillier@gmail.com depuis indentification_verif.php
 * revision :
 * ----------------------------------------------------------------------------
 * commentaires :
 * - en chantier V2 - pas completement teste
 * - les informations de connexion sont stockees dans $_SESSION
 * attention :
 * -
 * a faire :
 * - recuperer le code et le nom du club, meme si session 'prs'
 * ============================================================================
*/

session_start(); // doit etre la premiere instruction

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

/* TODO
if (isset($_GET['n_clb']))
  $_SESSION['n_clb'] = $_GET['n_clb'];
*/

if (isset($_GET['s'])) {
  $code_site = intval($_GET['s']);
  $_SESSION['swb'] = $code_site;
} else {
  header("location: ../../index.html");
  exit();
}
  
// --- recuperation des informations saisies dans le formulaire
if (!isset($_POST['id']) || !isset($_POST['mdp']))
  exit();

$identifiant = stripslashes(trim($_POST['id']));
$identifiant = strtolower($identifiant);
// peut etre un identifiant de club (maj et minuscules sont conserves)
// ou de personne (ou passe alors l'identifiant en minuscules (cf plus loin)
$mdp_clair = stripslashes(trim($_POST['mdp']));

$erreur_identification = (strlen($identifiant) === 0);
if ($erreur_identification) {
  header("location: ../../connexion.php?err=id&c=" . $code_club . "&s=" . $code_site);
  exit();
}
$erreur_mot_passe = false;

// ============================================================================
// --- classes utilisees
set_include_path('./../../');
  
// --- connection a la base de donnees
include 'php/bdd/base_donnees.php';

$bdd = Base_Donnees::acces();

include_once 'php/utilitaires/definir_locale.php';
require_once 'php/metier/calendrier.php' ;

require_once 'php/metier/club.php';
require_once 'php/bdd/enregistrement_club.php';

//require_once 'php/jour.php';
require_once 'php/bdd/enregistrement_membre.php';
require_once 'php/metier/membre.php';
//require_once 'php/permanence.php';

// ============================================================================
// --- verification de l'identite :


// Informations sur le club
// TODO: l'utilisateur doit etre un membre du club reference,
  
$club = new Club($code_club);
$enreg_club = new Enregistrement_Club();
$enreg_club->def_club($club);
$club_existe = $enreg_club->lire();

if ($club_existe) {
  $_SESSION['clb'] = $club->code();
  $_SESSION['id_clb'] = $club->sigle();
  $_SESSION['n_clb'] = $club->nom();
} else {
  header("location: ../../index.html");
}

// Verification des informations de connexion
// (identifiant, mdp) et personne autorisee a se connecter
  
$connexion = new Connexion();
$connexion->def_identifiant($identifiant);

$enreg_connexion = new Enregistrement_Connexion();
$enreg_connexion->def_connexion($connexion);

$code_membre = null;

try {
  $identification_ok = $enreg_connexion->verifier_identite($mdp_clair);
} catch (Erreur_Mot_Passe_Connexion $e) {
  header("location: ../../connexion.php?err=mdp&c=" . $code_club . "&s=" . $code_site);
  exit();
} catch (Erreur_Identifiant_Connexion $e) {
  header("location: ../../connexion.php?err=id&c=" . $code_club . "&s=" . $code_site);
  exit();
}

if (!$connexion->est_autorise()) {
  // Personne non autorisee a se connecter
  header("location: ../../connexion.php?err=cnx&c=" . $code_club . "&s=" . $code_site);
  exit();
}

// --- Utilisateur bien identifie et autorise a se connecter, on continue

$_SESSION['prs'] = true;
$membre = new Membre($connexion->code_membre());
$membre->def_connexion($connexion);

$enreg_membre = new Enregistrement_Membre();
$enreg_membre->def_membre($membre);
$enreg_membre->lire();

$utilisateur = $membre->prenom() . " " . $membre->nom();
$_SESSION['usr'] = $membre->code();
$_SESSION['n_usr'] = $utilisateur;
$_SESSION['cdb'] = $membre->est_chef_de_bord();
$_SESSION['act'] = $membre->est_actif(); // active = possibilite de s'inscrire

// A ce stade on peut considerer l'utilisateurice comme etant connecte.e 
$connexion->def_date_derniere_connexion(Calendrier::maintenant());
$enreg_connexion->modifier_date_derniere_connexion();
  
// Verifie si l'utilisateur est de permanence ou pas
$permanence = false;
/*
 Permanence::cette_semaine($perm);
 $permanence = $perm->a_comme_responsable($p);
 */
 if ($permanence)
  $_SESSION['prm'] = true;

// Teste si l'utilisateur est administrateur du systeme d'information
$admin = $enreg_membre->recherche_si_admin();
if ($admin)
  $_SESSION['adm'] = true;
//echo "Admin: " . $admin;


// ============================================================================
// --- Affichage contextuel en fonction du resultat de l'identification
//     cas d'une identification personnelle (cas du club traite plus haut)

if ($erreur_identification)
  header("location: ../../connexion.php?err=cnx&c=" . $code_club . "&s=" . $code_site);
elseif ($erreur_mot_passe)
  header("location: ../../connexion.php?err=mdp&c=" . $code_club . "&s=" . $code_site);
elseif (!$_SESSION['act']) {
  header("location: ../../accueil_perso.php");
 // header("location: ../../page_tableau_journalier_sorties.php?ta=os&d=" . Jour::aujourdhui()->jour);
} elseif ($permanence) {
  header("location: ../../accueil_perso.php");
  /*
  $j0 = date("N");
  $j = max(6, $j0);
  $jperm = mktime(0, 0, 0, date("m"), date("d") + $j - $j0, date("Y"));
  header("location: ../../page_tableau_journalier_sorties.php?ta=os&d=" . $jperm);
   */
} else {
  header("location: ../../accueil_perso.php");
}
exit();
// ============================================================================
?>
