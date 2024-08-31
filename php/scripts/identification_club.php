<?php
/* ============================================================================
 * Resabel - systeme de REServAtion de Bateau En Ligne
 * Copyright (C) 2024 Pierre Chevaillier
 * contact: pchevaillier@gmail.com 70 allee de Broceliande, 29200 Brest, France
 * ----------------------------------------------------------------------------
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License,
 * or any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 * ----------------------------------------------------------------------------
 * description : action suite soumission formulaire connexion club
 *  verifie les informations de connexion en tant que Club,
 *  donc sans utilisateur identifie de maniere personnelle
 * utilisation : php - require_once <chemin_vers_ce_fichier_php>
 * dependances :
 * ----------------------------------------------------------------------------
 * creation : 03-dec-2023 pchevaillier@gmail.com
 * revision : 31-aug-2024 pchevaillier@gmail.com * page accueil
 * ----------------------------------------------------------------------------
 * commentaires :
 * - les informations de connexion sont stockees dans $_SESSION
 * - connexion club : on va sur la page des incriptions equipages,
 *   en effet, c'est la raison principale pour se connecter.
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
  $url = '../../activites.php?a=ie&j=' . $jour->valeur_cle_date() . '&sa=1&pc=PT08H00M&dc=PT12H00M&ts=0&s=0';
  header('location: ' . $url);
}

exit();
// ============================================================================
?>
