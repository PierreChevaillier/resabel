<!DOCTYPE html>
<html lang="fr">
<?php
// ============================================================================
// contexte : Resabel - systeme de REServation de Bateaux En Ligne
// - Test composant Resabel pour generation de contenu dans une page web
// description : test affichage Cadre_Texte_Repliable
// copyright (c) 2023 AMP. Tous droits reserves.
// ----------------------------------------------------------------------------
// utilisation : navigateur web
// dependances :
// utilise avec :
//  - PHP 8.1 sur macOS 13.2
// ----------------------------------------------------------------------------
// creation : 18-mar-2023 pchevaillier@gmail.com
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
require_once 'php/elements_page/generiques/conteneur_repliable.php';

// ----------------------------------------------------------------------------
// --- Creation dynamique de la page
$feuilles_style = array();
$feuilles_style[] = "css/resabel_ecran.css";
$page = new Page_Simple("Resabel - Test", "Conteneur_Repliable", $feuilles_style);
$page->def_id("pge_tst");

// --- Affichage information sur le contenu de la page
$corps_page = new Element_Code();
$page->ajoute_contenu($corps_page);
$code_html = "<h1>Un cadre depliable/repliable</h1><p>Instance de <code>Conteneur_Repliable</code>.</p>";
$corps_page->def_code($code_html);

// --- Element sous test

$cadre = new Conteneur_Repliable(); //Cadre_Texte_Repliable();
$page->ajoute_contenu($cadre);
$cadre->def_id('X1');
$cadre->def_titre("Titre du cadre");

$contenu = new Element_Code();
$cadre->ajouter_element($contenu);
$contenu->def_code("<p>Corps de l'élément contenu dans le cadre</p>");
//$cadre->def_contenu("<p>Texte contenu dans le cadre</p>");

// --- Affichage de la page
$page->initialiser();
$page->afficher();
// ============================================================================
?>
</html>
