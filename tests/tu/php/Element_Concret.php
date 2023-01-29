<?php
// ==========================================================================
// contexte : Resabel - systeme de REServAtion de Bateau En Ligne
// description : Definition d'une classe concrete
//   heritant de la classe abstraite Element
//   requise uniquement pour les tests unitaires
// copyright (c) 2022-2023 AMP. Tous droits reserves.
// --------------------------------------------------------------------------
// utilisation : php - require_once <chemin_vers_ce_fichier_php>
// dependances :
// utilise avec : PHP 7.3 sur macOS 11.6
//                PHP 8.1 sur macOS 13.3
// --------------------------------------------------------------------------
// creation : 29-jan-2023 pchevaillier@gmail.com
// revision :
// --------------------------------------------------------------------------
// commentaires :
// - redefintion des methodes abstraites de la classe Element
// - definition d'attributs pour observer les appels de fonction
// attention :
// -
// a faire :
// -
// ==========================================================================

require_once './../../../php/elements_page/generiques/element.php';

// ==========================================================================

class Element_Concret extends Element {
  public string $debut = "Debut";
  public string $corps = "Corps";
  public string $fin = "Fin";
  public string $id = "";
  public function initialiser(): void {
    $this->debut = $this->debut . $this->id;
    $this->corps = $this->corps . $this->id;
    $this->fin = $this->fin . $this->id;
  }
  protected function afficher_debut(): void {
    print($this->debut);
  }
  
  protected function afficher_corps(): void {
    print($this->corps);
  }
  
  protected function afficher_fin(): void {
    print($this->fin);
  }
  
}
// ==========================================================================

?>
