<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : Affichage du calendrier des permanences futures
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : bootstrap 4.x
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 04-jun-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // ==========================================================================

  require_once 'php/metier/permanence.php';
  require_once 'php/elements_page/specifiques/vue_personne.php';
  
  // --------------------------------------------------------------------------
  class Table_Permanences extends Element {
    
    private $elements = array(); // elements de la table (1 ligne = 1 element)
    
    protected $menu_action;
    public function def_menu_action($menu) {
      $this->menu_action = $menu;
    }
    
    public function def_elements($liste_elements) {
      $this->elements = $liste_elements;
    }
    
    public function __construct($page) {
      $this->def_page($page);
    }
    
    public function initialiser() {
      // Rien de specifique a faire ici
    }
    
    protected function afficher_debut() {
      echo '<div class="container"><table class="table table-sm table-striped table-hover">';
      echo '<tbody>';
    }
    
    protected function afficher_menu_actions($personne) {
      if (isset($this->menu_action)) {
        // il n'y a pas necessairement de menu (depend du contexte)
        $this->menu_action->personne = $personne;
        $this->menu_action->initialiser();
        $this->menu_action->afficher();
      }
    }
    
    protected function afficher_corps() {
      
      $presentation_nom = new Afficheur_Nom();
      $presentation_tel = new Afficheur_telephone();
      $presentation_courriel = new Afficheur_Courriel_Actif();
      $sujet_courriel = "Permanence"; // pas de sujet particulier ici
      
      if (!isset($this->elements)) return; // on ne sait jamais...
      foreach ($this->elements as $perm) {
        echo '<tr>';
        echo '<td>' . $perm->semaine() . '</td>';
        echo '<td>' . $perm->jour_texte() . '</td>';
        $resp = $perm->responsable();
        $nom = '';
        $telephone = '';
        $courriel = '';
        if (isset($resp)) {
          $presentation_nom->def_personne($resp);
          $nom = $presentation_nom->formatter();
          $presentation_courriel->def_personne($resp);
          $telephone =  $presentation_tel->formatter($resp->telephone);
          $courriel = $presentation_courriel->formatter("Je te contacte car tu es de permanence la semaine du " . $perm->jour_texte() . ".",  $sujet_courriel);
        }
        echo '<td>' . $nom . '</td>';
        echo '<td><span>' . $telephone . '</span></td>';
        echo '<td>' . $courriel . '</td>';
        echo '<td>';
        $this->afficher_menu_actions($resp);
        echo '</td></tr>';
      }
    }
    
    protected function afficher_fin() {
      echo "</tbody></table></div>\n";
    }
  }
  
  // ==========================================================================
?>
