<?php
// ========================================================================
// description : definition de la classe Pied_Page
// utilisation : destine a etre affiche en bas de chaque page du site web
// teste avec  : PHP 5.5.3 sur Mac OS 10.11
// contexte    : Site web de l'association
// Copyright (c) 2017 AMP
// ------------------------------------------------------------------------
// creation: 22-juil-2017 pchevaillier@gmail.com depuis amp_france2018/pied_page.php
// revision: 22-juil-2017 pchevaillier@gmail.com version simple
// ------------------------------------------------------------------------
// commentaires :
// - affichage du copyright uniquement.
// attention :
// -
// a faire :
// ------------------------------------------------------------------------

// --- Classes utilisees
require_once 'php/elements_page/generiques/element.php';
  
// ------------------------------------------------------------------------
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
    echo '<p>' . $this->titre() . ' Copyright &copy; 2014 - ' . date('Y') . ' ' . Site_web::copyright() . '</p>';
  }
  

}
// ========================================================================
