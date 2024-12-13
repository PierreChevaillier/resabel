<!DOCTYPE html>
<html lang="fr">
<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 * description : generation page web pour inscription equipe a une activite
 * copyright (c) 2018-2024 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : navigateur web
 * dependances :
 * - voir directives include et require_once
 * - parametres $_SESSION et $_GET
 * - bootstrap 5.x
 * utilise avec :
 * - depuis 2023 :
 *   PHP 8.2 sur macOS 13.x
 *   PHP 8.1 sur hebergeur web
 * ----------------------------------------------------------------------------
 * creation : 09-jun-2019 pchevaillier@gmail.com maquette
 * revision : 26-jan-2024 pchevaillier@gmail.com init fonctionalites
 * revision : 15-fev-2024 pchevaillier@gmail.com + bouton abandon/retour
 * revision : 12-dec-2024 pchevaillier@gmail.com + detection suppression concurrente
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
// --- connection a la base de donnees
include 'php/bdd/base_donnees.php';
      
// --- Information sur le site Web
require_once 'php/bdd/enregistrement_site_web.php';
      
if (isset($_SESSION['swb']))
new Enregistrement_site_web($_SESSION['swb']);
      
// --- Classe definissant la page a afficher
require_once 'php/elements_page/generiques/page.php';
//require_once 'php/pages/page_activites.php';

// --- Classes des elements de la page
require_once 'php/elements_page/generiques/element.php';
require_once 'php/elements_page/generiques/entete_section.php';
require_once 'php/elements_page/generiques/modal.php';

require_once 'php/metier/calendrier.php';
require_once 'php/elements_page/specifiques/vue_seance_activite.php';
require_once 'php/elements_page/specifiques/formulaire_saisie_equipage.php';

require_once 'php/bdd/enregistrement_seance_activite.php';
require_once 'php/bdd/enregistrement_support_activite.php';
require_once 'php/bdd/enregistrement_membre.php';
require_once 'php/bdd/enregistrement_site_activite.php';

// ----------------------------------------------------------------------------
// --- Creation dynamique de la page

$feuilles_style = array();
$feuilles_style[] = "css/resabel_ecran.css";
$nom_site = Site_Web::accede()->sigle() . " Resabel";
$page = new Page_Simple($nom_site, "inscription équipage", $feuilles_style);

//$contexte = new Contexte_Action_Seance($page);
//$contexte->initialiser();
$page->ajouter_script('js/controle_inscription_equipage.js');

$page->fonction_onload = 'initialisation();';
// ----------------------------------------------------------------------------
// recherche des informations necessaires

$code_site_activite = $_GET['sa'];
$code_seance = $_GET['seance'];
$code_support = $_GET['support'];
$j = new Instant($_GET['j']);
$debut = $j->add(new DateInterval($_GET['hd']));
$fin = $j->add(new DateInterval($_GET['hf']));

if ($code_seance > 0) {
  $existe = Enregistrement_Seance_Activite::seance_existe($code_seance);
  if (!$existe) {
    // la seance a du etre supprimee entre temps.
    $code_seance = 0;
    /*
    $code_param_url = "?a=ie&j=" . $_GET['j']
      . "&sa=" . $code_site_activite
      . "&pc=" . $_GET['pc']
      . "&dc=" . $_GET['dc']
      . "&ts=" . $_GET['ts']
      . "&s=" . $_GET['s'];
    header("location: activites.php" . $code_param_url);
    exit();
     */
  }
}
// (1) les personnes actives (avec les details)
$personnes_actives = array();
$criteres = array();
$criteres['act'] = 1;
$ok = Enregistrement_Membre::collecter($criteres, '', '', $personnes_actives);

// (2) les seances progammees recoupant le creneau horaire
$seances = array();
$critere_selection = " date_debut < '" . $fin->date_heure_sql() . "' AND date_fin > '" . $debut->date_heure_sql() . "'";
$critere_tri =  "";
Enregistrement_Seance_Activite::collecter(null,
                                          $critere_selection,
                                          $critere_tri,
                                          $seances);

// (3) les personnes deja inscrites a une activite sur la plage horaire
//     donc que l'on ne peut pas inscrire sur cette seance
$personnes_occupees = array();
foreach ($seances as $s) {
  foreach ($s->inscriptions as $p) {
    $val = $p->participant->code();
    if (!in_array($val, $personnes_occupees))
        $personnes_occupees[] = $val;
  }
}
//echo "<p>n seances : " . count($seances) .  ' ' . $critere_selection . '</p>';
//echo "<p>n pers occuppes : " . count($personnes_occupees) . '</p>';

// referencer la seance courante ou creer 1 seance si existe pas
$seance = null;
if ($code_seance > 0) {
  $seance = Enregistrement_Seance_Activite::creer($code_seance);
  foreach ($seance->inscriptions as $p) {
    $p->participant = $personnes_actives[$p->participant->code()];
  }
  if ($seance->a_un_responsable())
    $seance->responsable = $personnes_actives[$seance->responsable->code()];
} else {
  $seance = new Seance_Activite();
  $j = new Instant($_GET['j']);
  $debut = $j->add(new DateInterval($_GET['hd']));
  $fin = $j->add(new DateInterval($_GET['hf']));
  $seance->plage_horaire = new Intervalle_temporel($debut, $fin);
}
$site_activite = Enregistrement_Site_Activite::creer($code_site_activite);
$seance->site = $site_activite;

// (3) le support sur lequel a lieu l'activite
$enreg_support = new Enregistrement_Support_Activite();
$enreg_support->lire($code_support);
$support = $enreg_support->support_activite();
$seance->def_support($support);

// ----------------------------------------------------------------------------
// --- Elements de la page

// Bandeau avec informations sur la seance
$element = new Entete_Section();
$titre = $seance->site->nom() . "</br> Inscription équipage sur " . $support->numero()
  . "</br>le " . $seance->debut()->date_texte()
  . " de " . $seance->debut()->heure_texte()
  . " à " . $seance->fin()->heure_texte();
$element->def_titre($titre);
$page->ajoute_element_haut($element);

// --- Informations utiles pour la saisie
$info = new Element_Code();
$code_html = '<div>' . PHP_EOL;

if ($seance->nombre_places_est_limite())
  $code_html = $code_html . '<p class="lead text-info">Places disponibles : '
. '<span id="nb_places_dispo">' . $seance->nombre_places_disponibles() . '</span></p>' . PHP_EOL;

if ($support->nombre_initiation_min > 0)
  $code_html = $code_html
  . '<p class="lead text-warning">Attention : <span id="nb_init_min">'
  . $support->nombre_initiation_min
  . '</span> places reservées pour initiation </p>';
if ($support->nombre_initiation_max > 0)
  $code_html = $code_html
  . '<p class="lead text-warning">Attention : pas plus de <span id="nb_init_max">'
  . $support->nombre_initiation_max
  . '</span> places pour initiation </p>';

$code_html = $code_html . '</div>' . PHP_EOL;
$info->def_code($code_html);
$page->ajoute_contenu($info);

// --- Composition courante de la seance
$vue_seance = new Afficheur_Vertical_Seance($page, $seance, null);
$vue_seance->def_id("aff_seance");
$vue_seance->est_interactif = false;

$afficheur = new Element_Code();
$code_aff = '<div class="container mx-auto" style="width:20rem;"><div class="row"><div class="col">';
$code_aff = $code_aff . $vue_seance->formater();
$code_aff = $code_aff . '</div></div></div>';
$afficheur->def_code($code_aff);
$page->ajoute_contenu($afficheur);

// --- bouton d'annulation (= retour a la page precedente)
$code_html = '<div class="container mx-auto" style="width:20rem;"><div class="container mx-auto" style="width:16rem;padding:4px;"><button class="btn btn-warning" onclick="window.location=document.referrer;">Abandonner saisie</button></div></div>';
$div_bouton = new Element_Code();
$page->ajoute_contenu($div_bouton);
$div_bouton->def_code($code_html);

// --- Boite modale pour affichage info requete
$afficheur_action = new Element_Modal();
$page->ajoute_contenu($afficheur_action);
$afficheur_action->def_id('aff_act');
$afficheur_action->def_titre('Inscription(s)');

// --- formulaire de saisie de l'equipage
$formulaire = new Formulaire_Saisie_Equipage($page, 'form_ie');
$page->ajoute_contenu($formulaire);
if ($seance->nombre_participants() > 0)
  $formulaire->def_titre("Saisie complément équipage");
else
  $formulaire->def_titre("Saisie nouvel équipage");
//$formulaire->confirmation_requise = false;

$formulaire->seance = $seance;
$formulaire->personnes_actives = $personnes_actives;
$formulaire->personnes_occupees = $personnes_occupees;

// ----------------------------------------------------------------------------
// --- Affichage de la page
$page->initialiser();
$page->afficher();
// ============================================================================
?>
</html>
