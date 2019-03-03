<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : collecte (pour l'instant), affichage de la liste des personnes
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : 
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 03-mar-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // -
  // a faire :
  // -
  // ==========================================================================

  require_once 'php/metier/personne.php';
  require_once 'php/bdd/enregistrement_membre.php';
  require_once 'php/utilitaires/format_donnees.php';
  
  
  class Table_Personnes extends Element {
    
    private $personnes = array();
    private $critere_selection = '';
    private $composante = '';
    private $role = '';
    private $legende = 'Liste des membres';
    
    public function initialiser() {
      $personnes = array();
      Enregistrement_Membre::collecter($this->critere_selection, $this->composante, $this->role, $this->personnes);
    }
    
    protected function afficher_debut() {
      echo '<div class="container"><table class="table table-sm table-striped table-hover">';
      if (strlen($this->legende) > 0)
        echo '<caption>' . $this->legende . '</caption>';
    }
    
    protected function afficher_corps() {
      echo '<p> n : ' . count($this->personnes) . '</p>';
      echo '<tbody>';
      foreach ($this->personnes as $p) {
        echo '<tr><td>' . $p->prenom . ' ' . $p->nom . '</td><td>' . formatter_num_tel_affichage($p->telephone) . '</td><td>' . $p->formatter_envoie_courriel("Je te contacte pour ") . '</td><td>' . $p->nom_commune . '</td></tr>';
      }
      echo '</tbody></table>';
    }
    
    protected function afficher_fin() {
      echo "</div>\n";
    }
  }
  
  
  
  // ==========================================================================
?>
