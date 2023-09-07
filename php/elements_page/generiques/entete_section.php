<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : Definition de la classe Entete_Section
  //  - Titre de section d'une page
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <nom_-fichier.php'
  // dependances : bootstrap 5.
  // teste avec : PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 02-mar-2019 pchevaillier@gmail.com
  // revision : 18-mar-2023 pchevaillier@gmail.com bootstrap v5.3
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // a faire :
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/element.php';
  
  // --------------------------------------------------------------------------
  class Entete_Section extends Element {
    
    public function initialiser() {
    }
    
    protected function afficher_debut() {
      echo '<div class=" mt-4 p-2 rounded entete_sect" style="padding:10px;text-align:center;">';
    }
    
    protected function afficher_corps() {
      echo '<h2>' . $this->titre() . '</h2>';
     }

    protected function afficher_fin() {
      echo '</div>';
    }

  }
  
  // ==========================================================================
?>
