<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 * description : Definition de la classe Formulaire_Connexion_Club
 * copyright (c) 2023-2023 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : php - require_once <chemin_vers_ce_fichier_php>
 * dependances :
 * - aucune
 * utilise avec :
 * - depuis 2023 :
 *   PHP 8.2 sur macOS 13.x
 *   PHP 8.1 sur hebergeur web
 * ----------------------------------------------------------------------------
 * creation : 03-dec-2023 pchevaillier@gmail.com
 * revision :
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * -
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
}

// ============================================================================
?>
