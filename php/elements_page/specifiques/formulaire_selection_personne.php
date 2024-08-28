<?php
/* ============================================================================
 * Resabel - systeme de REServAtion de Bateau En Ligne
 * Copyright (C) 2024 Pierre Chevaillier
 * contact: pchevaillier@gmail.com 70 allee de Broceliande, 29200 Brest, France
 * ----------------------------------------------------------------------------
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License,
 * or any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 * ----------------------------------------------------------------------------
 * description : formulaire de selection des personnes a afficher
 * utilisation : php - require_once <chemin_fichier.php>
 * dependances :
 * - variable $_GET, surtout quand on rafraichit la page
 * ----------------------------------------------------------------------------
 * creation : 29-mar-2019 pchevaillier@gmail.com
 * revision : 28-aug-2024 pchevaillier@gmail.com + reactivation_comptes
 * revision :
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * -
 * a faire :
 * -
 * ============================================================================
 */

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/formulaire.php';
  require_once 'php/elements_page/generiques/champ_formulaire.php';
  
   require_once 'php/bdd/enregistrement_commune.php';
  
  // ==========================================================================
  class Formulaire_Selection_Personne extends Formulaire {
    
    public function __construct($page) {
      $this->def_titre("Critères de sélection");
      $this->message_bouton_validation = "Afficher sélection";
      // personnes.php?a=l&act=1&cnx=1
      $this->methode = 'post';
      if($_GET['act'] == 1 && $_GET['cnx'] == 1)
        $script_traitement = "personnes.php?a=l&act=" . $_GET['act'] . "&cnx=" . $_GET['cnx'];
      else
        $script_traitement = "reactivation_comptes.php?a=l&act=" . $_GET['act'] . "&cnx=" . $_GET['cnx'];
      $action = 'm';
      $id = 'form_sel_prs';
      $page->javascripts[] = 'js/raz_formulaire.js';
      parent::__construct($page, $script_traitement, $action, $id);
    }
    
    public function initialiser() {
      $item = null;
      try {
        $item = new Champ_Nom("prn", "js/controle_saisie_nom.js", "verif_nom");
        if (isset($_POST['prn']) && $_POST['prn'] != "")
          $item->def_valeur($_POST['prn']);
        $item->def_titre("Prénom (début)");
        $this->ajouter_champ($item);
        
        $item = new Champ_Nom("nom", "js/controle_saisie_nom.js", "verif_nom");
        $item->def_titre("Nom (début)");
        if (isset($_POST['nom']) && $_POST['nom'] != "")
          $item->def_valeur($_POST['nom']);
        $this->ajouter_champ($item);
        
        $item = new Champ_Selection("cmn");
        $item->def_titre("Commune");
        $item->valeurs_multiples = false;
        $communes = array();
        Enregistrement_Commune::collecter("acces = 'O'"," nom ASC", $communes);
        $item->options[0] = "Toutes les communes";
        foreach ($communes as $code => $c)
          $item->options[$code] = $c->nom();
        if (isset($_POST['cmn']))
          $item->def_valeur($_POST['cmn']);
        $this->ajouter_champ($item);

        $item = new Champ_Selection('cdb');
        $item->def_titre("Chef.fe de bord");
        $item->options = array(0 => 'Tout le monde', 1 => 'Oui', 2 => 'Non');
        if (isset($_POST['cdb']))
          $item->def_valeur($_POST['cdb']);
        $this->ajouter_champ($item);
        
        $item = new Champ_Selection('niv');
        $item->def_titre("Expérience");
        $item->options = array(0 => 'Tout le monde', 1 => 'Débutant.e.s', 2 => 'Confirmé.e.s');
        if (isset($_POST['niv']))
          $item->def_valeur($_POST['niv']);
        $this->ajouter_champ($item);
        
        parent::initialiser();
      } catch(Exception $e) {
        die('Exception dans la methode initialiser de la classe Formulaire_Selection_Personne : ' . $e->getMessage());
      }
    }
    
    protected function afficher_corps() {
      parent::afficher_corps();
      
      echo '<div class="form-group form-btn" id="btn_raz" >';
      echo '<input type="button" class="btn btn-large btn-outline-secondary"';
      echo ' onclick="return raz_valeurs_formulaire(' . $this->id() . ')"';
      echo ' value="Supprimer les critères de sélection" >';
      echo '</div>';
      
    }
  }
  // ==========================================================================
?>
