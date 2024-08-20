<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 * description : Connexion 'Club' a Resabel
 * copyright (c) 2023-2023 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : navigateur web
 * dependances :
 * - aucune
 * utilise avec :
 *   PHP 8.2 sur macOS 13.x
 *   PHP 8.1 sur hebergeur web
 * ----------------------------------------------------------------------------
 * creation : 03-dec-2023 pchevaillier@gmail.com
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

include('php/utilitaires/definir_locale.php');
// ============================================================================
?>
<!DOCTYPE html>
<html lang="fr">
<?php
// ============================================================================

// --- connection a la base de donnees
include 'php/bdd/base_donnees.php';

// --- Information sur le site Web
require_once 'php/bdd/enregistrement_site_web.php';

if (!isset($_GET['s']))
  die("erreur : valeur non definie");
if (preg_match('/[0-9]/', $_GET['s']))
  new Enregistrement_site_web($_GET['s']);
else
  die("erreur : valeur invalide");

// --- Classe definissant la page a afficher
require_once 'php/pages/page_connexion_club.php';

// --- Classes des elements de la page
require_once 'php/elements_page/generiques/element.php';
// ============================================================================
// ----------------------------------------------------------------------------
// --- Creation dynamique de la page

$feuilles_style = array();
$feuilles_style[] = "css/resabel_ecran.css";
$nom_site = Site_Web::accede()->sigle() . " Resabel";

$page = new Page_Connexion_Club($nom_site, "connexion_club", $feuilles_style);

/*
$info = new Element_Code();
$info->def_code('<div class="alert alert-warning" role="alert">version de développement incomplète</div>');
$page->ajoute_contenu($info);
*/

// --- Affichage de la page
$page->initialiser();
$page->afficher();
// ============================================================================
?>
</html>
