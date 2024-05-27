<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 * description : Definition de la classe Page_Indisponibilite
 *               creation ou modification d'une indisponibilite
 * copyright (c) 2023-2024 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : php require_once <chemin_vers_ce_fichier.php>
 * dependances :
 * - aucune
 * utilise avec :
 *   PHP 8.2 sur macOS 13.x
 *   PHP 8.1 sur hebergeur web
 * ----------------------------------------------------------------------------
 * creation : 03-dec-2023 pchevaillier@gmail.com
 * revision : é&-may-2024 pchevaillier@gmail.com 1re version operationnelle
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
require_once 'php/elements_page/specifiques/page_menu.php';
require_once 'php/elements_page/generiques/entete_contenu_page.php';
require_once 'php/elements_page/specifiques/formulaire_indisponibilite.php';
require_once 'php/bdd/enregistrement_indisponibilite.php';

// ----------------------------------------------------------------------------
class Page_Indisponibilite extends Page_Menu {
  
  private $form = null;
  
  public function definir_elements() {
    
    parent::definir_elements();
    
    // Creation & configuration des elements de la page
    // contextuelle selon le type d'action et le type d'indisponibilite
    $creation =  (isset($_GET['act']) && $_GET['act'] == 'c');
    $modification =  (isset($_GET['act']) && $_GET['act'] == 'm');
    
    $code_indispo = (isset($_GET['id']))? intval($_GET['id']): 0;
    $type_indispo = (isset($_GET['typ']))? intval($_GET['typ']): 0;
    
    $fermeture_site = ($type_indispo == enregistrement_Indisponibilite::CODE_TYPE_INDISPO_SITE);
    $indispo_support = ($type_indispo == enregistrement_Indisponibilite::CODE_TYPE_INDISPO_SUPPORT);
    
    $element = new Entete_Contenu_Page();
    $titre_action = ($creation) ? "Nouvelle" : "Modification";
    $titre_objet = ($fermeture_site) ? " fermeture" : " indisponibilité";
    $titre = $titre_action . $titre_objet;
    $element->def_titre($titre);
    $this->ajoute_element_haut($element);
    
    // Initialisation des informations sur l'indisponibilite
    $indispo = null;
    if ($fermeture_site)
      $indispo = new Fermeture_Site($code_indispo);
    else
      $indispo = new Indisponibilite_support($code_indispo);
    
    if ($modification) {
      /* L'indisponibilite existe deja
       * On va initialiser le formulaire avec les informations enregistrees
       * dans la base de donnees
      */
      $enregistrement = new enregistrement_Indisponibilite();
      $enregistrement->def_indisponibilite($indispo);
      $enregistrement->lire();
      $indispo = $enregistrement->indisponibilite(); // car modifie par lire
    }
    
    // Creation du formulaire pour la modification des informations
    $formulaire = new Formulaire_Indisponibilite($this, $indispo);
    $this->ajoute_contenu($formulaire);
    $this->form = $formulaire;
  }
  
  public function initialiser() {
    parent::initialiser();
    $this->form->initialiser_champs();
  }
}
// ============================================================================
?>
