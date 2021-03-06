<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : collecte (pour l'instant), affichage de la liste des personnes
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : bootstrap 4.x
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 03-mar-2019 pchevaillier@gmail.com
  // revision : 04-mar-2019 pchevaillier@gmail.com utlisation Vue_Personne
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // -
  // a faire :
  // -
  // ==========================================================================

  require_once 'php/metier/personne.php';
  require_once 'php/elements_page/specifiques/vue_personne.php';
  require_once 'php/bdd/enregistrement_membre.php';
  require_once 'php/utilitaires/format_donnees.php';
  
  // --------------------------------------------------------------------------
  class Table_Personnes extends Element {
    
    private $personnes = array();
    
    protected $menu_action_personne;
    public function def_menu_action($element) {
      $this->menu_action_personne = $element;
    }
    
    public function def_personnes($liste_personnes) {
      $this->personnes = $liste_personnes;
    }
    
    //private $legende = 'Liste des membres';
    
    public function __construct($page) {
      $this->def_page($page);
    }
    
    public function initialiser() {
      // Rien de specifique a faire ici
    }
    
    protected function afficher_debut() {
      echo '<p>Nombre de personnes : ' . count($this->personnes) . '</p>';
      echo '<div class="container"><table class="table table-sm table-striped table-hover">';
      echo '<tbody>';
      //if (strlen($this->legende) > 0)
      //  echo '<caption>' . $this->legende . '</caption>';
    }
    
    protected function afficher_menu_actions($personne) {
      if (isset($this->menu_action_personne)) {
        // il n'y a pas necessairement de menu (depend du contexte)
        $this->menu_action_personne->personne = $personne;
        $this->menu_action_personne->initialiser();
        $this->menu_action_personne->afficher();
      }
    }
    
    protected function afficher_corps() {
      $presentation_nom = new Afficheur_Nom();
      $presentation_tel = new Afficheur_telephone();
      $presentation_courriel = new Afficheur_Courriel_Actif();
      $sujet_courriel = ""; // pas de sujet particulier ici
      
      if (!isset($this->personnes)) return; // on ne sait jamais...
      foreach ($this->personnes as $p) {
        $presentation_nom->def_personne($p);
        $presentation_courriel->def_personne($p);
        echo '<tr><td>' . $presentation_nom->formatter() . '</td>';
        echo '<td><span>' . $presentation_tel->formatter($p->telephone) . '</span></td>';
        //echo '<td>' . $presentation_courriel->formatter("Je te contacte pour ",  $sujet_courriel) . '</td><td>' . $p->nom_commune . '</td>';
        echo '<td>';
        $this->afficher_menu_actions($p);
        echo '</td></tr>';
      }
    }
    
    protected function afficher_fin() {
      echo "</tbody></table></div>\n";
    }
  }
  
  // ==========================================================================
?>
