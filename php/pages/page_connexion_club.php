<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 * description : Definition de la classe Page_Connexion_Club
 * copyright (c) 2023-2023 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : php require_once <chemin_vers_ce_fichier.php>
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

// --- Classes utilisees
require_once 'php/elements_page/generiques/page.php';

require_once 'php/elements_page/specifiques/entete_connexion.php';
require_once 'php/elements_page/specifiques/formulaire_connexion_club.php';

// --------------------------------------------------------------------------
class Page_Connexion_Club extends Page_Simple {
  
  protected function inclure_meta_donnees_open_graph() {
  }
  
  public function definir_elements() {
    $element = new Entete_Connexion();
    $nom_club = isset($_GET['n_clb'])? $_GET['n_clb']: "AMP";
    $titre = Site_Web::accede()->sigle();
    $element->def_titre($titre);
    $element->sous_titre = "Resabel";
    $this->ajoute_element_haut($element);
    
    $messages_erreur = new Element_Code();
    $this->ajoute_contenu($messages_erreur);
    
    // formulaire de connexion
    $script = "php/scripts/identification_club.php?c=" . $_GET['c'] . "&s=" . $_GET['s'];
    $action = 'a'; // TODO: je ne sais pas a quoi ca sert ici
    $id = 'form_cnx_clb';
    $formulaire = new Formulaire_Connexion_Club($this, $script, $action, $id);
    $this->ajoute_contenu($formulaire);
  }
}
// ============================================================================
?>
