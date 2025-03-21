<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : affichage d'une fenetre modale vide
  // copyright (c) 2018-2023 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : bootstrap 5.x (teste avec bootstrap 5.3)
  // utilise avec :
  // - PHP 8.2 sur macOS 13.2
  // --------------------------------------------------------------------------
  // creation : 04-avr-2019 pchevaillier@gmail.com
  // revision : 21-mar-2023 pchevaillier@gmail.com bootstrap v5.3
  // --------------------------------------------------------------------------
  // commentaires :
  // - https://getbootstrap.com/docs/5.3/components/modal/
  // - Fait pour etre utlise avec un script js qui remplit le corps du composant
  // attention :
  // -
  // a faire :
  // -
  // ==========================================================================

// --- Classes utilisees
require_once 'php/elements_page/generiques/element.php';

  // --------------------------------------------------------------------------
  class Element_Modal extends Element {
    public string $corps = '';
    
    public function initialiser() {
      $this->corps = '<p>YYYYYYYYY - ' . $this->id() . '</p>';
    }
    
    protected function afficher_debut() {
      $html_id = (strlen($this->id()) > 0) ? " id=\"" . $this->id() . "\" " : " ";
      echo '<div class="modal" tabindex="-1"' . $html_id . 'role="dialog">';
      echo '<div class="modal-dialog" role="document">';
      echo '<div class="modal-content rounded-4 shadow">'; // https://getbootstrap.com/docs/5.3/examples/modals/
    }

    protected function afficher_tete_contenu(): void {
      echo '<div class="modal-header">';
      echo '<h4 class="modal-title" id="' . $this->id() . '_titre">' . $this->titre() . '</h4>';
      echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>';
      echo '</div>';
    }
    
    protected function afficher_corps_contenu(): void {
      echo '<div class="modal-body" id="' . $this->id() . '_corps">';
      echo $this->corps;
      echo '</div>';
    }
    
    protected function afficher_pied_contenu(): void {
      echo '<div class="modal-footer flex-column align-items-stretch w-100 gap-2 pb-3 border-top-0">'; // https://getbootstrap.com/docs/5.3/examples/modals/
      echo '<button type="button" role="button" class="btn btn-secondary" id="' . $this->id() . '_btn" data-bs-dismiss="modal">Fermer</button>';
      echo '</div>';
    }
    
    protected function afficher_corps() {
      $this->afficher_tete_contenu();
      $this->afficher_corps_contenu();
      $this->afficher_pied_contenu();
    }
    
    protected function afficher_fin() {
      echo '</div></div></div>';
    }
  }
  
  // ==========================================================================
?>
