<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : Classe definissant les elements d'un formulaire de connexion
  // copyright (c) 2018-2023 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <nom_-fichier.php>
  // dependances :
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 06-oct-2018 pchevaillier@gmail.com
  // revision : 01-dec-2023 pchevaillier@gmail.com suppr. constructeur
  // --------------------------------------------------------------------------
  // commentaires :
  // - Plusieurs contextes d'utilisation, donc parametres differents
  //   a initialiser dans le contexte d'instanciation.
  // attention :
  // - 
  // a faire :
  // - script controle saisie : cryptage du mot de passe
  // ==========================================================================

// --- Classes utilisees
require_once 'php/elements_page/generiques/formulaire.php';
require_once 'php/elements_page/generiques/champ_formulaire.php';
  
// ============================================================================
class Formulaire_Connexion extends Formulaire {

  public function initialiser() {
    $item = null;
    try {
      $item = new Champ_Identifiant("id");
      $this->ajouter_champ($item);
      
      $item = new Champ_Mot_Passe("mdp"); //, "js/controle_identification.js");
      $this->ajouter_champ($item);
      
      parent::initialiser();
    } catch(Exception $e) {
      die('Exception dans la methode initialiser de la classe Formulaire_Connexion : ' . $e->getMessage());
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
