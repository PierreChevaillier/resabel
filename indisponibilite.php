<!DOCTYPE html>
<html lang="fr">
<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 * description : page web permettant la saise ou modification
 *               d'une indisponibilite (support indisponible ou fermeture site)
 * copyright (c) 2023-2023 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : navigateur web
 * dependances :
 * - aucune
 * utilise avec :
 *   PHP 8.2 sur macOS 13.x
 *   PHP 8.1 sur hebergeur web
 * ----------------------------------------------------------------------------
 * creation : 06-mai-2024 pchevaillier@gmail.com
 * revision :
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * -
 * a faire :
 *-
 * ============================================================================
 */
set_include_path('./');
include('php/utilitaires/controle_session.php');
include('php/utilitaires/definir_locale.php');

// ============================================================================

// --- connection a la base de donnees
include 'php/bdd/base_donnees.php';

// --- Information sur le site Web
require_once 'php/bdd/enregistrement_site_web.php';

// --- Classe definissant la page a afficher
require_once 'php/pages/page_indisponibilite.php';

// --- Classes des elements de la page
require_once 'php/elements_page/generiques/element.php';
// ============================================================================
// parametres
$code_site_web = (isset($_SESSION['swb']))? intval($_SESSION['swb']): 1;

// ----------------------------------------------------------------------------
// --- Creation dynamique de la page

$feuilles_style = array();
$feuilles_style[] = "css/resabel_ecran.css";
new Enregistrement_site_web($code_site_web);
$nom_site = Site_Web::accede()->sigle() . " Resabel";

$page = new Page_Indisponibilite($nom_site, "indisponibilite", $feuilles_style);

$info = new Element_Code();
$info->def_code('<div class="alert alert-warning" role="alert">version de développement incomplète</div>');
$page->ajoute_contenu($info);

// --- Affichage de la page
$page->initialiser();
$page->afficher();
// ============================================================================
?>
</html>
