<?php
/* ============================================================================
 * Resabel - systeme de REServAtion de Bateau En Ligne
 * Copyright (C) 2024 Pierre Chevaillier
 * contact: pchevaillier@gmail.com 70 allee de Broceliande, 29200 Brest, France
 *
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
 * description : definition de la classe Pied_Page
 *               contenu affiche en bas de chaque page du site web
 * utilisation : php - require_once <chemin_vers_ce_fichier_php>
 * dependances :
 * - aucune
 * utilise avec :
 *   PHP 8.1 sur hebergeur web
 * ----------------------------------------------------------------------------
 * creation : 09-jun-2019 pchevaillier@gmail.com
 * revision : 21-aug-2024 pchevaillier@gmail.com + notice GNU GPLv3
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
require_once 'php/elements_page/generiques/element.php';
  
// ----------------------------------------------------------------------------
// --- Definition de la classe Pied_Page

/**
 * @author Pierre Chevaillier
 */
class Pied_Page extends Element {

  public function initialiser() {
    // rien a faire de particulier
  }
  
  /**
    *
    */
  protected function afficher_debut() {
    echo '<footer>';
  }
  
  protected function afficher_corps() {
    $this->afficher_copyright();
  }
  
  protected function afficher_fin() {
    echo '</footer>';
  }
  
  private function afficher_copyright() {
    /*
     * Aout 2024 (=lanvement v2) : definition licence et copyright
     */
    echo '<p><a href="https://github.com/PierreChevaillier/resabel/wiki" target="blank">' . $this->titre() . '</a> Copyright &copy; 2024 Pierre Chevaillier</p>';
  }
  

}
// ========================================================================
