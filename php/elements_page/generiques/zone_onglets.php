<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : Definition de la classe Zone_Onglets
  // copyright (c) 2018-2023 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <nom_-fichier.php'>
  // dependances : bootstrap 5.x
  // utilise avec : booststrap 5.2,
  //                PHP 8.2 sur macOS 13.1
  // --------------------------------------------------------------------------
  // creation : 15-jun-2019 pchevaillier@gmail.com
  // revision : 18-mar-2023 pchevaillier@gmail.com bootstrap v5.2
  // --------------------------------------------------------------------------
  // commentaires :
  // - https://getbootstrap.com/docs/5.3/components/navs-tabs/ (section Navs & tabs)
  // attention :
  // a faire :
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/element.php';
  
  // --------------------------------------------------------------------------
  class Zone_Onglets extends Conteneur_Elements {
    
    protected function afficher_debut() {
      echo '<div style="padding:1px">';
      echo '<ul class="nav nav-tabs" id="' . $this->id() . '" role="tablist">';
      $n = 1;
      foreach ($this->elements as $onglet) {
        echo '<li class="nav-item" role="presentation">';
        echo '<button class="nav-link ' . (($n == 1) ? 'active': '' ) . '" id="' . $onglet->id() . '-tab" data-bs-toggle="tab" data-bs-target="#' . $onglet->id() . '-tab-pan" type="button" role="tab" aria-controls="' . $onglet->id()  . '-tab-pan" aria-selected=' . (($n == 1) ? '"true"': '"false" ' )  . '>' . $onglet->titre() . '</button>';
        echo '</li>';
        $n += 1;
      }
      echo "</ul>";
    }
    
    protected function afficher_corps() {
      echo '<div class="tab-content" id="' . $this->id() . '-tab-cont">';
      $n = 1;
      foreach ($this->elements as $onglet) {
        echo '<div class="tab-pane fade ' . (($n == 1) ? ' show active ': '' ) . '" id="' . $onglet->id() . '-tab-pan" role="tabpanel" aria-labelledby="' . $onglet->id() . '-tab" tabindex="0">';
        $onglet->afficher_corps();
        echo '</div>';
        $n += 1;
      }
      echo '</div>';
     }

    protected function afficher_fin() {
      echo '</div>';
    }

  }
  
  // ==========================================================================
?>
