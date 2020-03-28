<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : affichage d'une fenetre modale vide
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : bootstrap 4.x (teste avec bootstrap 4.3.1)
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 04-avr-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // - https://getbootstrap.com/docs/4.3/components/modal/
  // Fait pour etre utlisee avec un script (typiquement AJAX) qui sert a remplir
  // le corps de la fenetre
  // attention :
  // - en construction
  // a faire :
  // - a tester
  // ==========================================================================

  // --------------------------------------------------------------------------
  class Element_Modal extends Element {
    //public $id_modal_body = '';
    public $corps = '<p>YYYYYYYYY</p>';
    
    public function initialiser() {
    }
    
    protected function afficher_debut() {
      echo '<div class="modal" tabindex="-1" id="' . $this->id() . '" role="dialog">';
      echo '<div class="modal-dialog" role="document">';
      echo '<div class="modal-content">';
    }

    protected function afficher_tete_contenu() {
      echo '<div class="modal-header">';
      echo '<h4 class="modal-title" id="' . $this->id() . '_titre">' . $this->titre() . '</h4>';
      echo '<button type="button" class="close" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>';
      echo '</div>';
    }
    
    protected function afficher_corps_contenu() {
      echo "\n<div class=\"modal-body\" id=\"" . $this->id() . "_corps\">\n";
      echo $this->corps;
      echo "\n</div>\n";
    }
    
    protected function afficher_pied_contenu() {
      echo '<div class="modal-footer">';
      echo '<button type="button" role="button" class="btn" id="' . $this->id() . '_btn" data-dismiss="modal">Fermer</button>';
      echo '</div>';
    }
    
    protected function afficher_corps() {
      $this->afficher_tete_contenu();
      $this->afficher_corps_contenu();
      $this->afficher_pied_contenu();
    }
    
    protected function afficher_fin() {
      echo "\n</div></div></div>\n";
    }
  }
  
  // ==========================================================================
?>
