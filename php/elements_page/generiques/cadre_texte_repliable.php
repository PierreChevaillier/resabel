<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : affichage d'un cadre repliable (collapsible)
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : bootstrap 4.1 (teste avec 4.1.3)
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 02-mar-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // - https://getbootstrap.com/docs/4.1/components/collapse/
  // attention :
  // a faire :
  // ==========================================================================

  // --------------------------------------------------------------------------
  class Cadre_Texte_Repliable extends Element {
    private $contenu = '';
    public function def_contenu($code_html) { $this->contenu = $code_html; }
    
    public function initialiser() {
    }
    
    protected function afficher_debut() {
      echo '<div class="accordion" id="accordion_' . $this->id() . '"><div class="card"><div class="card-header" id="heading_' . $this->id() . '">';
      echo '<h5 class="mb-0"><button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse_' . $this->id() . '" aria-expanded="false" aria-controls="collapse_' . $this->id() . '">' . $this->titre() . '</button></h5></div>';
    }
    
    protected function afficher_corps() {
      echo '<div id="collapse_' . $this->id() . '" class="collapse" aria-labelledby="heading_' . $this->id() . '" data-parent="#accordion_' . $this->id() . '"><div class="card-body">' . $this->contenu . ' </div></div>';
    }
    
    protected function afficher_fin() {
      echo "</div></div>\n";
    }
  }
  
  // ==========================================================================
?>
