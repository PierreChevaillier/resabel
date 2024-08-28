<!DOCTYPE html>
<html lang="fr">
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
 * description : creation dynamique de la page pour la (re/des)activation
 *               du compte d'une personne
 * utilisation : php - navigateur web
 * dependances :
 * - variable $_SESSION
 * - variable $_GET, surtout quand on rafraichit la page
 * ----------------------------------------------------------------------------
 * creation : 28-aug-2024 pchevaillier@gmail.com
 * revision :
 * ----------------------------------------------------------------------------
 * commentaires :
 * - Fragile: c'est un copier/coller de personnes.php
 * attention :
 * - Si on modifie personnes.php, il faut certainement modifier ce fichier aussi
 * a faire :
 * -
 * ============================================================================
 */

set_include_path('./');
include('php/utilitaires/controle_session.php');
include('php/utilitaires/definir_locale.php');

// ----------------------------------------------------------------------------
// Verification acces a cette fonctionnalite
// normalement le controle est fait en amont, dans le menu de l'application

include 'php/metier/profil_session.php';

$profil = new Profil_Session();
//$profil->initialiser();

// Fonctionnalite uniquement offerte a un.e administrateurice Resabel
$possible = $profil->est_admin();
if (!$possible) {
  header("location: index.html");
  die("erreur : autorisation requise");
}

// Verification des parametres
// parametre obligatoire pas de valeur par defaut)
if (!isset($_GET['a']))
  die("erreur : action non definie");
if (!preg_match('/[l]/', $_GET['a']))
  die("erreur : action invalide");

// parametres optionnels: criteres de selection pour l'affichage
// de la liste des personnes
$criteres_selection = array();
if (isset($_GET['act']) && preg_match('/[01]/', $_GET['act']))
    if (isset($_SESSION['adm']))
      // seuls les utilisateurs avec le privilege 'admin' peuvent
      // acceder aux informations sur les comptes inactifs
      $criteres_selection['act'] = $_GET['act'];
    else
      $criteres_selection['act'] = 1; // pas admin => que les actifs
if (isset($_GET['cnx']) && preg_match('/[01]/', $_GET['cnx']))
    $criteres_selection['cnx'] = $_GET['cnx'];

if (isset($_POST['prn']) && $_POST['prn'] != "")
  $criteres_selection['prn'] = $_POST['prn'];

if (isset($_POST['nom']) && $_POST['nom'] != "")
  $criteres_selection['nom'] = $_POST['nom'];

if (isset($_POST['cmn']) && $_POST['cmn'] != 0)
  $criteres_selection['cmn'] = $_POST['cmn'];

if (isset($_POST['cdb'])&& $_POST['cdb'] != 0)
  $criteres_selection['cdb'] = $_POST['cdb'];

if (isset($_POST['niv'])&& $_POST['niv'] != 0)
  $criteres_selection['niv'] = $_POST['niv'];

// ----------------------------------------------------------------------------
// --- connection a la base de donnees
include 'php/bdd/base_donnees.php';

// --- Information sur le site Web
require_once 'php/bdd/enregistrement_site_web.php';

if (isset($_SESSION['swb']))
  new Enregistrement_site_web($_SESSION['swb']);

// --- Classe definissant la page a afficher
require_once 'php/pages/page_personnes.php';

// ----------------------------------------------------------------------------
// --- Creation dynamique de la page

$feuilles_style = array();
$feuilles_style[] = "css/resabel_ecran.css";
$nom_site = Site_Web::accede()->sigle() . " Resabel";
$page = new Page_Personnes($nom_site, "Réactivation compte membres", $feuilles_style);

$page->criteres_selection = $criteres_selection;

// --- Affichage de la page
$page->initialiser();
$page->afficher();
// ============================================================================
?>
</html>
