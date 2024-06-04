<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : Affichage des supports d'activite
  // copyright (c) 2018-2024 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : bootstrap 4.x
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 28-aug-2020 pchevaillier@gmail.com
  // revision : 31-may-2024 pchevaillier@gmail.com + affichages usage et actif
  // --------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // ==========================================================================

  require_once 'php/metier/support_activite.php';
  //require_once 'php/bdd/enregistrement_support_activite.php';
  
  
  abstract class Table_elements extends Element {
    protected $elements = array(); // elements de la table (1 ligne = 1 element)
    
    public $affiche_creation = false;
    
    protected $menu_action = null;
    public function def_menu_action($menu) {
      $this->menu_action = $menu;
    }
    
    public function def_elements($liste_elements) {
      $this->elements = $liste_elements;
    }
    /*
    public function __construct($page) {
      $this->def_page($page);
    }
    */
    public function initialiser() {
      // Rien de specifique a faire ici
    }
    
    protected function afficher_debut() {
      echo '<div class="container"><table class="table table-sm table-striped table-hover">';
      echo '<tbody>';
    }
    
    protected function afficher_menu_actions($item) {
      if (!is_null($this->menu_action)) {  // il n'y a pas necessairement de menu (depend du contexte)
        $this->menu_action->def_objet($item);
        $this->menu_action->initialiser();
        $this->menu_action->afficher();
      }
    }
    
    protected function afficher_fin() {
      echo "</tbody>\n";
      echo "</table></div>\n";
    }

  }
  
  // --------------------------------------------------------------------------
class Table_Supports_activite extends Table_Elements {
  
  protected function afficher_corps() {
    
    if (!isset($this->elements)) return; // on ne sait jamais...
    
    echo '<tr><th style="text-align:center;">Numéro</th><th>Nom</th><th>Type</th><th>Usage</th><th>Service</th><th>Nb place</th>';
    if (!is_null($this->menu_action))
      echo '<th>&nbsp;</th>';
    echo '</tr>';
    
    foreach ($this->elements as $item) {
      if ($item->est_actif() || isset($_SESSION['adm'])) {
        echo '<tr>';
        //if (is_a($item, 'Bateau'))
           echo '<td style="text-align:center;">'. $item->numero() . '</td>';
        //else
        //   echo '<td>&nbsp;</td>';
        echo '<td>'. $item->nom() . '</td>';
        echo '<td>'. $item->type->nom() . '</td>';

        $usage = '';
        if ($item->est_pour_competition()) $usage = 'compet';
        elseif ($item->est_pour_loisir()) $usage = 'loisir';
        echo '<td>' . $usage . '</td>';
        
        $service = '';
        if ($item->est_actif()) $service = 'actif';
        else $service = 'désarmé';
        
        echo '<td>' . $service. '</td>';
        echo '<td>' . $item->capacite() . '</td>';
        echo '<td>';
        $this->afficher_menu_actions($item);
        echo '</td></tr>';
      }
    }
  }
  
  protected function afficher_fin() {
    echo "</tbody></table></div>\n";
  }
}

// ============================================================================
?>
