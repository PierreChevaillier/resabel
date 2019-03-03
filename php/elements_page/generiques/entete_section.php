<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : Definition de la classe Entete_Section
  //  - Tittre de section d'une page
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <nom_-fichier.php'
  // dependances : bootstrap 4.x
  // teste avec : PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 02-mar-2019 pchevaillier@gmail.com
  // revision :
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
      echo '<div class="jumbotron entete_sect" style="padding:5px;text-align:center;">';
    }
    
    protected function afficher_corps() {
      echo "<h2 class=\"display-6\">" . $this->titre() . "</h2>";
     }

    protected function afficher_fin() {
      echo "</div>\n";
    }

  }
  
  // ==========================================================================
?>
