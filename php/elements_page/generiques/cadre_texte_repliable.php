<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : affichage d'un cadre repliable (collapsible)
  // copyright (c) 2018-2023 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : bootstrap 5.3 (teste avec 5.3.0)
  // utilise avec :
  // - PHP 8.2 sur macOS 13.2
  // --------------------------------------------------------------------------
  // creation : 02-mar-2019 pchevaillier@gmail.com
  // revision : 18-mar-2023 pchevaillier@gmail.com bootstrap v5.3
  // --------------------------------------------------------------------------
  // commentaires :
  // - https://getbootstrap.com/docs/5.3/components/collapse/
  // attention :
  // - accordion ne marche pas avec bootstrap 5.3.0-alpha1 (ne se ferme pas)
  // a faire :
  // - tester avec nouvelle version bootstrap 5.3
  // ==========================================================================

  // --------------------------------------------------------------------------
  class Cadre_Texte_Repliable extends Element {
    private string $contenu = '';
    public function def_contenu(string $code_html): void { $this->contenu = $code_html; }
    
    public function initialiser() {
    }
    
    protected function afficher_debut() {
      echo '<div class="accordion" id="accord_' . $this->id() . '">';
      echo '<div class="accordion-item">';
      echo '<h2 class="accordion-header" id="accord_head_' . $this->id() . '">';
      echo '<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_' . $this->id() . '" aria-expanded="true" aria-controls="collapse_' . $this->id() . '">' . $this->titre() . '</button></h2>';
      echo '<div id="collapse_' . $this->id() . '" class="accordion-collapse collapse" aria-labelledby="accord_head_' .  $this->id() . '" data-bs-parent="#accord_' . $this->id() . '">';
      //echo '<div class="accordion" id="accordion_' . $this->id() . '"><div class="card"><div class="card-header" id="heading_' . $this->id() . '">';
      //echo '<h5 class="mb-0"><button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse_' . $this->id() . '" aria-expanded="false" aria-controls="collapse_' . $this->id() . '">' . $this->titre() . '</button></h5></div>';
    }
    
    protected function afficher_corps() {
      echo '<div class="accordion-body">' . $this->contenu . '</div>';
      //echo '<div id="collapse_' . $this->id() . '" class="collapse" aria-labelledby="heading_' . $this->id() . '" data-parent="#accordion_' . $this->id() . '"><div class="card-body">' . $this->contenu . ' </div></div>';
    }
    
    protected function afficher_fin() {
      echo '</div></div></div>';
    }
  }
  
  // ==========================================================================
?>
