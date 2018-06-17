<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : Definition de la classe Entete_Connexion 
  // copyright (c) 2018 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <nom_-fichier.php'
  // dependances :
  // teste avec : PHP 5.5.3 sur Mac OS 10.11 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 17-jun-2018 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // -
  // a faire :
  // -
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/element.php';
  
  // --------------------------------------------------------------------------
  class Entete_Connexion extends Element {
    
    
    public function initialiser() {
    }
    
    protected function afficher_debut() {
      echo '<div class="jumbotron" style="padding:20px;text-align:center;">';
    }
    
    protected function afficher_corps() {
      echo "<h1 class=\"display-4\">" . $this->titre() . "</h1><p class=\"lead\">Inscription pour les Sorties en Mer</p>";
     }

    protected function afficher_fin() {
      echo "</div>\n";
    }

  }
  
  // ==========================================================================
?>
