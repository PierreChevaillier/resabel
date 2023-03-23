<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : element pour inclusion cadre (iframe) responsive
  // copyright (c) 2018-2023 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : bootstrap 4.x (teste avec bootstrap 4.1.3)
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 02-mar-2019 pchevaillier@gmail.com
  // revision : 17-mar-2023 pchevaillier@gmail.com bootstrap v5.3
  // --------------------------------------------------------------------------
  // commentaires :
  // - https://getbootstrap.com/docs/5.3/helpers/ratio/
  // attention :
  // -
  // a faire :
  // -
  // ==========================================================================

  // --------------------------------------------------------------------------
  class Cadre_Inclusion extends Element {
    private $source = '';
    private $ratio = '1x1';
    public function def_source($code_html) { $this->source = $code_html; }
    public function def_ratio($expression_ratio) { return $this->ratio = $expression_ratio; }
    
    public function initialiser() {
    }
    
    protected function afficher_debut() {
       echo '<div class="ratio ratio-' . $this->ratio . '">';
    }
    
    protected function afficher_corps() {
      //echo '<iframe class="embed-responsive-item" style="margin:0px; padding:5px;" src="' . $this->source . '"></iframe>';
      echo '<iframe src="' . $this->source . '"></iframe>';
    }
    
    protected function afficher_fin() {
      echo "</div>";
    }
  }
  
  // ==========================================================================
?>
