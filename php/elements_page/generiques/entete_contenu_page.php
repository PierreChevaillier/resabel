<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : Definition de la classe Entete_Contenu_Page
  //  - information sur la page : indique la fonction de la page
  // copyright (c) 2018-2023 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <nom_-fichier.php'
  // dependances : bootstrap 5.x
  // teste avec : PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 08-dec-2018 pchevaillier@gmail.com
  // revision : 18-mar-2023 pchevaillier@gmail.com bootstrap v5.3
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
  class Entete_Contenu_Page extends Element {
    
    public $sous_titre = "";
    
    public function initialiser() {
    }
    
    protected function afficher_debut() {
      echo '<div class="mt-4 p-2 rounded" style="padding:20px;text-align:center;">';
    }
    
    protected function afficher_corps() {
      echo '<h1>' . $this->titre() . '</h1>';
      if (strlen($this->sous_titre) > 0)
        echo '<p class="lead">' . $this->sous_titre . '</p>';
     }

    protected function afficher_fin() {
      echo '</div>';
    }

  }
  
  // ==========================================================================
?>
