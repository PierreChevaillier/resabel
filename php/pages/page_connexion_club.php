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
 * description : Definition de la classe Page_Connexion_Club
 * utilisation : php - require_once <chemin_vers_ce_fichier_php>
 * dependances :
 * - bootstrap 5.x
 *   CONTENU = classe Formulaire_Connexion_Club
 * ----------------------------------------------------------------------------
 * creation : 03-dec-2023 pchevaillier@gmail.com
 * revision : 27-sep-2024 pchevaillier@gmail.com
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * - afficher_debut() definit partiellement la structure du contenu de la page
 *   cf. classe Formulaire_Connexion_Club
 * a faire :
 *-
 * ============================================================================
 */

// --- Classes utilisees
require_once 'php/elements_page/generiques/page.php';

require_once 'php/elements_page/specifiques/entete_connexion.php';
require_once 'php/elements_page/specifiques/formulaire_connexion_club.php';

// --------------------------------------------------------------------------
class Page_Connexion_Club extends Page_Simple {
  
  protected function inclure_meta_donnees_open_graph() {
  }
  
  public function definir_elements() {
    /*
    $element = new Entete_Connexion();
    $nom_club = isset($_GET['n_clb'])? $_GET['n_clb']: "AMP";
    $titre = Site_Web::accede()->sigle();
    $element->def_titre($titre);
    $element->sous_titre = "Resabel";
    $this->ajoute_element_haut($element);
    
    $messages_erreur = new Element_Code();
    $this->ajoute_contenu($messages_erreur);
    */
    
    // formulaire de connexion
    $script = "php/scripts/identification_club.php?c=" . $_GET['c'] . "&s=" . $_GET['s'];
    $action = 'a'; // TODO: je ne sais pas a quoi ca sert ici
    $id = 'form_cnx_clb';
    $formulaire = new Formulaire_Connexion_Club($this, $script, $action, $id);
    $this->ajoute_contenu($formulaire);
  }
  
  protected function afficher_debut(): void {
    parent::afficher_debut();
    $titre = Site_Web::accede()->sigle();
    $sous_titre = "Resabel";
    echo '<div class="mx-auto" style="width:40%">'; // pour que ce soit centre horizontalement
    
    echo '<div class="card" style="width:20rem;">'; // pour controler la largeur du formulaire
    echo '<div class="card-header">';
    echo '<div class="my-3 p-3 rounded bg-primary" style="text-align:center;color:white;">';
    echo '<h1>'. $titre . '</h1><p class="lead">' . $sous_titre . '</p>';
    echo '</div>';
    echo '</div>';
  }
  
  protected function afficher_fin(): void {
    echo '</div></div>';
    parent::afficher_fin();
  }
}
// ============================================================================
?>
