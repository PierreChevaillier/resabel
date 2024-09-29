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
 * description : Definition de la classe Formulaire_Connexion_Club
 * utilisation : php - require_once <chemin_vers_ce_fichier_php>
 * dependances :
 * - bootstrap 5.x
 * - CONTEXTE : Page_Connexion_Club
 * ----------------------------------------------------------------------------
 * creation : 03-dec-2023 pchevaillier@gmail.com
 * revision : 27-sep-2024 pchevaillier@gmail.com
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * - Le formulaire DOIT ETRE dans le corps d'une carte (cf. afficher_debut)
 * a faire :
 *-
 * ============================================================================
 */

// --- Classes utilisees
require_once 'php/elements_page/generiques/formulaire.php';
require_once 'php/elements_page/generiques/champ_formulaire.php';

// ============================================================================
class Formulaire_Connexion_Club extends Formulaire {

  public function initialiser() {
    $item = null;
    try {
      $item = new Champ_Mot_Passe("mdp"); //, "js/controle_identification.js");
      $this->ajouter_champ($item);
      
      parent::initialiser();
    } catch(Exception $e) {
      die('Exception dans la methode initialiser de la classe Formulaire_Connexion_Club : ' . $e->getMessage());
    }
  }
  
  protected function afficher_debut(): void {
    echo '<div class="card-body m-0 p-0">';
    parent::afficher_debut();
  }
  
  protected function afficher_fin(): void {
    parent::afficher_fin();
    echo '</div>';
  }
}

// ============================================================================
?>
