<!DOCTYPE html>
<html lang="fr">
<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 * description : creation dynamique de la page pour la (re)activation
 *               du compte d'une personne
 * copyright (c) 2018-2024 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : navigateur web
 * dependances :
 * - variable $_SESSION
 * utilise avec :
 * - depuis 2023 :
 *   PHP 8.2 sur macOS 13.x
 *   PHP 8.1 sur hebergeur web
 * ----------------------------------------------------------------------------
 * creation : 30-jul-2024 pchevaillier@gmail.com
 * revision :
 * ----------------------------------------------------------------------------
 * commentaires :
 * - en evolution
 * attention :
 * -
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
$profil->initialiser();

// Fonctionnalite uniquement offerte a un.e administrateurice Resabel
$possible = $profil->est_admin();
if (!$possible) {
  header("location: index.html");
  die("erreur : autorisation requise");
}

// ----------------------------------------------------------------------------
// --- connection a la base de donnees
include 'php/bdd/base_donnees.php';

// --- Information sur le site Web
require_once 'php/bdd/enregistrement_site_web.php';

if (isset($_SESSION['swb']))
  new Enregistrement_site_web($_SESSION['swb']);

// --- Classe definissant la page a afficher
require_once 'php/pages/page_reactivation_comptes.php';

// ----------------------------------------------------------------------------
// --- Creation dynamique de la page

$feuilles_style = array();
$feuilles_style[] = "css/resabel_ecran.css";
$nom_site = Site_Web::accede()->sigle() . " Resabel";
$page = new Page_Reactivation_Comptes($nom_site, "Réactivation compte membres", $feuilles_style);

$page->criteres_selection = $criteres_selection;

// --- Affichage de la page
$page->initialiser();
$page->afficher();
// ============================================================================
?>
</html>
