<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 * description : classes definissant les 'vues' (= 'presentations')
 *               d'un objet de la classe Indisponibilite
 * copyright (c) 2018-2024 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : php - require_once <chemin_vers_ce_fichier_php>
 * dependances :
 * - bootstrap 5.x
 * - javascript : actions_indisponibilite.js (controle actions menu)
 * utilise avec :
 * - depuis 2023 :
 *   PHP 8.2 sur macOS 13.x
 *   PHP 8.1 sur hebergeur web
 * ----------------------------------------------------------------------------
 * creation : 30-avr-2024 pchevaillier@gmail.com
 * revision : 22-may-2024 pchevaillier@gmail.com + Afficheur_Fermetures_Site
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * -
 * a faire :
 * -
 * ============================================================================
 */

// --- Classes utilisees
require_once 'php/metier/indisponibilite.php';

require_once 'php/elements_page/generiques/element.php';
require_once 'php/elements_page/generiques/modal.php';
require_once 'php/elements_page/generiques/page.php';

// ============================================================================
class Menu_Actions_Indisponibilite extends Element {
  private ?Indisponibilite $indisponibilite;
  public function def_objet(Indisponibilite $objet_metier): void {
    $this->indisponibilite = $objet_metier;
  }
  
  public ?Element_Modal $afficheur_info = null;
  public ?Element_Modal $afficheur_action = null;
  
  public function __construct(Page $page) {
    $this->def_page($page);
    
    // ajout des fichiers des scripts associes aux actions du menu
    $page->ajouter_script('js/actions_indisponibilite.js');
  
    // Element modal pour affichage des informations sur l'objet
    $this->afficheur_info = new Element_Modal();
    $this->afficheur_info->def_id('aff_indispo');
    $this->afficheur_info->def_titre('Informations Indisponibilité');
    $this->page->ajoute_contenu($this->afficheur_info);
    
    // Element modal pour indiquer le resultat d'une action
    $this->afficheur_action = new Element_Modal();
    $this->afficheur_action->def_id('aff_act_indispo');
    $this->afficheur_action->def_titre('Action effectuée');
    $this->page->ajoute_contenu($this->afficheur_action);
  }
  
  public function initialiser() {
  }
  
  protected function afficher_debut() {
    echo '<div class="dropdown"><button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>';
    echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
  }
  
  protected function afficher_actions(): void {
    $type_objet = (is_a($this->indisponibilite, 'Indisponibilite_Support')) ? 1 : 2;
    $periode = $this->indisponibilite->formatter_periode();
    $html_resume_indispo = '<p>'
      . $this->indisponibilite->libelle_objet() . '<br />'
      . $periode . '<br />'
      . $this->indisponibilite->motif()->nom()
      .'</p>';
    $html_details_indispo = '<p>'
    . $this->indisponibilite->information() . '<br />'
    . 'saisie le ' . $this->indisponibilite->instant_creation()->date_texte()
    . ' à ' . $this->indisponibilite->instant_creation()->heure_texte()
    . ' par ' . $this->indisponibilite->identite_createurice() . '<br />'
    . '</p>';
    
    $params = $this->indisponibilite->code()
      . ", " . $type_objet
      . ", '" . $this->afficheur_info->id()
      . "', '" . $html_resume_indispo
      . "', '" . $html_details_indispo
      . "'";
    
    echo '<a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#' . $this->afficheur_info->id()
      . '"  onclick="return afficher_indisponibilite(' .  $params . ');">Afficher</a>';
    
    echo '<a class="dropdown-item" href="indisponibilite.php?act=m&typ=' . $type_objet . '&id=' . $this->indisponibilite->code() . '">Modifier</a>';
    
    $params = $this->indisponibilite->code()
      . ", " . $type_objet
      . ", '" . $this->afficheur_action->id()
      . "', '" . $html_resume_indispo
      . "'";
    
    echo '<a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#' . $this->afficheur_action->id()
      . '"  onclick="return activer_controle_suppression_indisponibilite(' . $params . ');">Supprimer</a>';
  }

  protected function afficher_corps() {
    $this->afficher_actions();
  }
  
  protected function afficher_fin() {
    echo '</div></div>';
  }
  
}

// ============================================================================
class Afficheur_Fermetures_Site extends Element_Code {
  public function __construct(Page $page, & $fermetures) {
    $this->def_page($page);
    $code_html = '<div class="alert alert-warning" role="alert" align="center"><p class="lead"> Le site est fermé : <br />';
    foreach ($fermetures as $fermeture) {
      $code_html = $code_html
        . $fermeture->libelle_motif() . ' '
        . $fermeture->formatter_periode() . ' : '
        . $fermeture->information()
        . '<br />';
    }
    $code_html = $code_html . '</p></div>';
    $this->def_code($code_html);
  }
}
// ============================================================================
?>
