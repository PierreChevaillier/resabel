<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : affichage d'une fenetre modale vide
  // copyright (c) 2018-2023 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : bootstrap 5.x (teste avec bootstrap 5.2)
  // utilise avec :
  // - PHP 8.2 sur macOS 13.2
  // --------------------------------------------------------------------------
  // creation : 04-avr-2019 pchevaillier@gmail.com
  // revision : 21-mar-2023 pchevaillier@gmail.com bootstrap v5.2
  // --------------------------------------------------------------------------
  // commentaires :
  // - https://getbootstrap.com/docs/5.3/components/modal/
  // - Fait pour etre utlise avec un script js (typiquement htmlRequest)
  //   qui sert a remplir le corps du composant
  // attention :
  // -
  // a faire :
  // - tester avec bs 5.3
  // ==========================================================================

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
      echo '<div class="modal-content">';
    }

    protected function afficher_tete_contenu(): void {
      echo '<div class="modal-header">';
      echo '<h4 class="modal-title" id="' . $this->id() . '_titre">' . $this->titre() . '</h4>';
//      echo '<button type="button" class="close" data-bs-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>';
      echo '<button type="button" class="close" data-bs-dismiss="modal" aria-label="Fermer"></button>';
      echo '</div>';
    }
    
    protected function afficher_corps_contenu(): void {
      echo '<div class="modal-body" id="' . $this->id() . '_corps">';
      echo $this->corps;
      echo '</div>';
    }
    
    protected function afficher_pied_contenu(): void {
      echo '<div class="modal-footer">';
      echo '<button type="button" role="button" class="btn" id="' . $this->id() . '_btn" data-bs-dismiss="modal">Fermer</button>';
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
