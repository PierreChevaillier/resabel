<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : Affichage des seances d'activite, par support et par creneau horaire
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : bootstrap 4.x
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 15-jun-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // ==========================================================================

  //require_once 'php/metier/activite.php';
  require_once 'php/metier/calendrier.php';
  
  require_once 'php/metier/support_activite.php';
  require_once 'php/bdd/enregistrement_support_activite.php';
  
  require_once 'php/metier/site_activite.php';
  require_once 'php/bdd/enregistrement_site_activite.php';
  
  // --------------------------------------------------------------------------
  class Table_Seances extends Element {
    
    private $activite_site;
    
    public $affiche_creation = false;
    
    protected $menu_action;
    public function def_menu_action($menu) {
      $this->menu_action = $menu;
    }
    
    public function __construct($page, $activite_site) {
      $this->def_page($page);
      $this->activite_site = $activite_site;
    }
    
    public function initialiser() {
      // Rien de specifique a faire ici ?
      // definir les creneaux a partir de la plage horaire
    }
    
    protected function afficher_debut() {
      echo "\n";
      echo '<div class="container-fluid" style="padding:0px;"><table class="table table-hover">';
      echo '<thead><tr><th></th>';
      foreach ($this->activite_site->creneaux_activite as $creneau) {
        echo '<th>', $creneau->format('H:i'), '</th>';
      }
      echo '</tr></thead><tbody>';
    }
    
    protected function afficher_menu_actions($item) {
      if (isset($this->menu_action)) {
        // il n'y a pas necessairement de menu (depend du contexte)
        $this->menu_action->personne = $item;
        $this->menu_action->initialiser();
        $this->menu_action->afficher();
      }
    }
    
    protected function afficher_corps() {
      $cal = calendrier::obtenir();
      
      $presentation_nom = new Afficheur_Nom();
      
      if (!isset($this->activite_site->site->supports_activite)) return; // on ne sait jamais...
      foreach ($this->activite_site->site->supports_activite as $code => $support) {
        echo '<tr>';
        if (is_a($support, 'Bateau')) {
          echo '<td class="cel_bateau compet"><div class="num_bateau">'. $support->numero() . '</div><div class="nom_bateau">' .  $support->nom() . '</div></td>';
        } elseif (is_a($support, 'Plateau_Ergo'))  {
          echo '<td>' . $support->nom() . '</td>';
        }
//        echo '<td>';
//        $this->afficher_menu_actions($item);
        echo '</tr>';
      }
    }
    
    protected function afficher_fin() {
      echo "</tbody></table></div>\n";
    }
  }
  
  // ==========================================================================
?>
