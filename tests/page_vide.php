<!DOCTYPE html>
<html lang="fr">
<?php
// ============================================================================
// contexte : Resabel - systeme de REServation de Bateaux En Ligne
// - Test composant Resabel pour generation de contenu dans une page web
// - modele de fichier source
// description : genere une page web "vide"
// copyright (c) 2023 AMP. Tous droits reserves.
// ----------------------------------------------------------------------------
// utilisation : navigateur web
// dependances :
// utilise avec :
//  - PHP 8.2 sur macOS 13.2
// ----------------------------------------------------------------------------
// creation : 02-oct-2017 pchevaillier@gmail.com
// revision : 23-mar-2023 pchevaillier@gmail.com essa
// ----------------------------------------------------------------------------
// commentaires :
//  - affiche juste du texte pour s'assurer qu'il se passe bien quelque chose
// attention :
// a faire :
// ============================================================================

set_include_path('./../');
      
// --- Classe definissant la page a afficher
require_once 'php/elements_page/generiques/page.php';

// --- Classes des elements de la page
require_once 'php/elements_page/generiques/element.php';

// ----------------------------------------------------------------------------
// --- Creation dynamique de la page

$feuilles_style = array();
$feuilles_style[] = "css/resabel_ecran.css";
$page = new Page_Simple("AMP - Resabel - Test", "Page vide", $feuilles_style);

$code_html = "<h1> Page vide</h1>\n<p>Cette page est intentionnellement (presque) vide.</p>";
$corps_page = new Element_Code();
$page->ajoute_contenu($corps_page);
$corps_page->def_code($code_html);
      
// --- Affichage de la page
$page->initialiser();
$page->afficher();
// ============================================================================
?>
</html>
