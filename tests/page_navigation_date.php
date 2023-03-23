<!DOCTYPE html>
<html lang="fr">
<?php
// ============================================================================
// contexte : Resabel - systeme de REServation de Bateaux En Ligne
// - Test composant Resabel pour generation de contenu dans une page web
// description : test affichage + controle Menu_navigation_Date
// copyright (c) 2023 AMP. Tous droits reserves.
// ----------------------------------------------------------------------------
// utilisation : navigateur web
// dependances :
// utilise avec :
//  - PHP 8.2 sur macOS 13.2
// ----------------------------------------------------------------------------
// creation : 18-mar-2023 pchevaillier@gmail.com essai avec bootstrap v5.3
// revision :
// ----------------------------------------------------------------------------
// commentaires :
//  - pour le test
// attention :
// a faire :
// - tester avec nouvelle version bootstrap v5.3
// ============================================================================
set_include_path('./../');

// --- Classe definissant la page a afficher
require_once 'php/elements_page/generiques/page.php';

// --- Classes des elements de la page
require_once 'php/elements_page/generiques/element.php';
require_once 'php/elements_page/specifiques/controleur_date_page.php';

require_once 'php/metier/calendrier.php';
// ----------------------------------------------------------------------------
// --- Creation dynamique de la page
$feuilles_style = array();
$feuilles_style[] = "css/resabel_ecran.css";
$page = new Page_Simple("Resabel - Test", "Navigation date", $feuilles_style);

// --- Affichage information sur le contenu de la page
$corps_page = new Element_Code();
$page->ajoute_contenu($corps_page);
$code_html = "<h1>Une page avec un menu de navigation dates</h1>";
$corps_page->def_code($code_html);

// --- Element sous test
$menu = new Menu_Navigation_Date();
$page->ajoute_contenu($menu);
$menu->def_id('X1');
$menu->def_titre("Changer date");
$menu->page_cible = "page_navigation_date.php";

if (isset($_GET["j"])) {
  $menu->date_ref = new Instant($_GET["j"]);
} else {
  $menu->date_ref = Calendrier::aujourdhui();
}
// --- Affichage de la page
$page->initialiser();
$page->afficher();
// ============================================================================
?>
</html>
