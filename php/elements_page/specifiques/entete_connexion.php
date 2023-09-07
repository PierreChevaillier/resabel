<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : Definition de la classe Entete_Connexion
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances :
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 17-jun-2018 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // ==========================================================================
  
  // --- Classes utilisees
  require_once 'php/elements_page/generiques/element.php';
  
  // --------------------------------------------------------------------------
  class Entete_Connexion extends Element {
    
    public string $sous_titre = "";
    public function initialiser() { }
    
    protected function afficher_debut() {
      echo '<div style="padding:40px;"><div class="mt-4 p-2 rounded bg-primary" style="padding:10px;text-align:center;color:white;">';
    }
    
    protected function afficher_corps() {
      echo '<h1>' . $this->titre() . '</h1>';
      echo '<p class="lead">' . $this->sous_titre . '</p>';
    }
    
    protected function afficher_fin() {
      echo '</div></div>';
    }
    
  }
  
  // ==========================================================================
?>
