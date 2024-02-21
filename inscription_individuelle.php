<!DOCTYPE html>
<html lang="fr">
<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 * description :  page pour l'inscription de l'utilisateur connecte a
 *               une seance d'activite : formulaire de recherche
 *               de disponibilite d'un support disponible pour une plage
 *               de creneaux horaires
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
 * creation : 10-jul-2019 pchevaillier@gmail.com
 * revision : 21-fev-2024 pchevaillier@gmail.com modif controle access, mise en page
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
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
// Verification acces a cette page
// Fonctionnalite uniquement offerte a un utilisateur connecte
// identifie comme un membre actif
// ou pour une sessnion 'club'
//
// Normalement le menu de l'application ne permet pas d'arriver ici
// mais si ce n'est pas le cas...

$possible = (isset($_SESSION['clb']) && $_SESSION['clb']) || (isset($_SESSION['prs']) && $_SESSION['prs'] && $_SESSION['usr'] && $_SESSION['act']);
if (!$possible) {
  header("location: index.html");
  die("erreur : valeur non definie");
}

// ----------------------------------------------------------------------------
// --- connection a la base de donnees
include 'php/bdd/base_donnees.php';

// --- Information sur le site Web
require_once 'php/bdd/enregistrement_site_web.php';

if (isset($_SESSION['swb']))
  new Enregistrement_site_web($_SESSION['swb']);

// --- Classe definissant la page a afficher
require_once 'php/pages/page_inscription_individuelle.php';

// ----------------------------------------------------------------------------
// --- Creation dynamique de la page

$feuilles_style = array();
$feuilles_style[] = "css/resabel_ecran.css";
$nom_site = Site_Web::accede()->sigle() . " Resabel";
$page = new Page_Inscription_Individuelle($nom_site, "Filtre séances activités", $feuilles_style);

// --- Affichage de la page
$page->initialiser();
$page->afficher();
// ============================================================================
?>
</html>
