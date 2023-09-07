<!DOCTYPE html>
<html lang="fr">
<?php
// ============================================================================
// contexte : Resabel - systeme de REServation de Bateaux En Ligne
// - Test composant Resabel pour generation de contenu dans une page web
// description : test affichage Zone_Onglets
// copyright (c) 2023 AMP. Tous droits reserves.
// ----------------------------------------------------------------------------
// utilisation : navigateur web
// dependances :
// utilise avec :
//  - PHP 8.1 sur macOS 13.2
// ----------------------------------------------------------------------------
// creation : 18-mar-2023 pchevaillier@gmail.com essai avec bootstrap v5.2
// revision :
// ----------------------------------------------------------------------------
// commentaires :
//  -
// attention :
// a faire :
// ============================================================================
set_include_path('./../');

// --- Classe definissant la page a afficher
require_once 'php/elements_page/generiques/page.php';

// --- Classes des elements de la page
require_once 'php/elements_page/generiques/element.php';
require_once 'php/elements_page/generiques/zone_onglets.php';

// ----------------------------------------------------------------------------
// --- Creation dynamique de la page
$feuilles_style = array();
$feuilles_style[] = "css/resabel_ecran.css";
$page = new Page_Simple("Resabel - Test", "Zone_Onglets", $feuilles_style);

// --- Affichage information sur le contenu de la page
$corps_page = new Element_Code();
$page->ajoute_contenu($corps_page);
$code_html = "<h1>Une page avec un conteneur de type onglets</h1>";
$corps_page->def_code($code_html);

// --- Element sous test
$conteneur = new Zone_Onglets();
$page->ajoute_contenu($conteneur);
$conteneur->def_id('X1');
$conteneur->def_titre("Titre du conteneur");

$contenu1 = new Element_Code();
$contenu1->def_id('X1-tab1');
$contenu1->def_titre('Onglet 1');
$conteneur->ajouter_element($contenu1);
$contenu1->def_code("<p>Contenu de l'onglet1</p>");

$contenu2 = new Element_Code();
$contenu2->def_id('X1-tab2');
$contenu2->def_titre('Onglet 2');
$conteneur->ajouter_element($contenu2);
$contenu2->def_code("<p>Contenu de l'onglet2</p>");

// --- Affichage de la page
$page->initialiser();
$page->afficher();
// ============================================================================
?>
</html>
