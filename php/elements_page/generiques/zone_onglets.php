<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : Definition de la classe Zone_Onglets
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <nom_-fichier.php'
  // dependances : bootstrap 4.x
  // teste avec : booststrap 4.3,
  //              PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 15-jun-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // - https://getbootstrap.com/docs/4.3/components/navs/ (section Tabs)
  // attention :
  // a faire :
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/element.php';
  
  // --------------------------------------------------------------------------
  class Zone_Onglets extends Conteneur_Elements {
    
    protected function afficher_debut() {
      echo "\n";
      echo '<div style="padding:5px">';
      echo '<ul class="nav nav-tabs" id="' . $this->id() . '" role="tablist">';
      $n = 1;
      foreach ($this->elements as $onglet) {
        echo '<li class="nav-item">';
        echo '<a class="nav-link ' . (($n == 1) ? 'active': '' ) . '" id="' . $onglet->id() . '-tab" data-toggle="tab" href="#' . $onglet->id() . '" role="tab" aria-controls="' . $onglet->id()  . '" aria-selected=' . (($n == 1) ? '"true"': '"false" ' )  . '>' . $onglet->titre() . '</a>';
        echo '</li>';
        $n += 1;
      }
      echo "</ul>\n";
    }
    
    protected function afficher_corps() {
      echo '<div class="tab-content" id="' . $this->id() . '_taabContent">';
      $n = 1;
      foreach ($this->elements as $onglet) {
        echo '<div class="tab-pane fade ' . (($n == 1) ? ' show active ': '' ) . '" id="' . $onglet->id() . '" role="tabpanel" aria-labelledby="' . $onglet->id() . '-tab">';
        $onglet->afficher_corps();
        echo '</div>';
        $n += 1;
      }
      echo "</div>\n";
     }

    protected function afficher_fin() {
      echo "</div>\n";
    }

  }
  
  // ==========================================================================
?>
