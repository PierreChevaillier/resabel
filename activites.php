<!DOCTYPE html>
<html lang="fr">
<?php
// ============================================================================
// contexte : Resabel - systeme de REServation de Bateaux En Ligne
// description : generation page web de gestion des activites d'une journee
// copyright (c) 2018-2023 AMP. Tous droits réserves.
// ----------------------------------------------------------------------------
// utilisation : navigateur web (cote serveur)
// dependances :
// - $_SESSION
// - jQuery et jQuery-UI
// teste avec : PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur hebergeur web
// ----------------------------------------------------------------------------
// creation : 12-jun-2019 pchevaillier@gmail.com
// revision :
// ----------------------------------------------------------------------------
// commentaires :
// - jQuery UI utilise dans actions_seance_activite.js (autocomplete)
// attention :
// a faire :
// ============================================================================

set_include_path('./');
include('php/utilitaires/controle_session.php');
include('php/utilitaires/definir_locale.php');

// ----------------------------------------------------------------------------
// --- connection a la base de donnees
include 'php/bdd/base_donnees.php';
      
// --- Information sur le site Web
require_once 'php/bdd/enregistrement_site_web.php';
      
if (isset($_SESSION['swb']))
new Enregistrement_site_web($_SESSION['swb']);
      
// --- Classe definissant la page a afficher
require_once 'php/pages/page_activites.php';

// --- Classes des elements de la page
require_once 'php/elements_page/specifiques/vue_seance_activite.php';

// ----------------------------------------------------------------------------
// --- Creation dynamique de la page
$feuilles_style = array();
$feuilles_style[] = "css/resabel_ecran.css";
$feuilles_style[] = "./../jquery-ui/1.13.2/jquery-ui.css";
$nom_site = Site_Web::accede()->sigle() . " Resabel";
$titre = "séances du jour";
if (isset($_GET['a'])) {
  if ($_GET['a'] == 'l')
    $titre = " Vue " . $titre;
  elseif ($_GET['a'] == 'ii' || $_GET['a'] == 'ie')
    $titre = "Inscpriptions séance";
}
$page = new Page_Activites($nom_site, $titre, $feuilles_style);

$page->ajouter_script('./../jquery/3.6.3/jquery.min.js');
$page->ajouter_script('./../jquery-ui/1.13.2/jquery-ui.min.js');

$contexte = new Contexte_Action_Seance($page);
$contexte->initialiser();
$page->def_contexte_action($contexte);

// --- Affichage de la page
$page->initialiser();
$page->afficher();
// ============================================================================
?>
</html>
