<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : affichage d'un conteneur repliable (collapsible) d'elements
  // copyright (c) 2018-2023 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : bootstrap (teste avec 5.3.0)
  // utilise avec :
  // - PHP 8.2 sur macOS 13.2
  // --------------------------------------------------------------------------
  // creation : 02-mar-2019 pchevaillier@gmail.com
  // revision : 18-mar-2023 pchevaillier@gmail.com bootstrap v5.3
  // --------------------------------------------------------------------------
  // commentaires :
  // - Utilise https://getbootstrap.com/docs/5.3/components/accordion/
  // attention :
  // a faire :
  // --------------------------------------------------------------------------
  class Conteneur_Repliable extends Conteneur_Elements {
    
    //private $symbole_deplier = '<img src="../../assets/icons/arrows-expand.svg" alt="+" width="24" height="24" title="Démasquer contenu">';
    //private $symbole_plier = '<img src="../../assets/icons/arrows-collapse.svg" alt="-" width="24" height="24" title="Masquer contenu">';
 
    protected function afficher_debut() {
      echo '<div class="accordion" id="accord_' . $this->id() . '">';
      echo '<div class="accordion-item">';
      echo '<h2 class="accordion-header" id="accord_head_' . $this->id() . '">';
      echo '<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_' . $this->id() . '" aria-expanded="true" aria-controls="collapse_' . $this->id() . '">' . $this->titre() . '</button></h2>';
      echo '<div id="collapse_' . $this->id() . '" class="accordion-collapse collapse" aria-labelledby="accord_head_' .  $this->id() . '" data-bs-parent="#accord_' . $this->id() . '">';
      echo '<div class="accordion-body">';
    }
        
    protected function afficher_fin() {
      echo '</div></div></div></div>';
    }
  }
  
  // ==========================================================================
?>
