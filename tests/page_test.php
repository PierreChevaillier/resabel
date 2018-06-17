<?php
  // ===========================================================================
  // description : definition de la classe Page_Test
  //               Page simple pour les tests des classes PHP
  // utilisation : Site web -  inclusion dans page web dynamique
  // teste avec  : PHP 5.5.3 sur Mac OS 10.11 ; PHP 7.0 sur serveur OVH
  // contexte    : Systeme d'information dfer l'AMP
  // Copyright (c) 2017-2018 AMP
  // ---------------------------------------------------------------------------
  // creation : 05-jan-2018 pchevaillier@gmail.com
  // revision :
  // ---------------------------------------------------------------------------
  // commentaires :
  // - uniquement pour les tests
  // attention :
  // -
  // a faire :
  // ---------------------------------------------------------------------------

  // --- Classes utilisees
  require_once 'generiques/page.php';
  //require_once 'elements/entete_image.php';
  require_once 'elements/menu_principal.php';
  //require_once 'elements/bandeau_partenaires.php';
  //require_once 'elements/bandeau_reseaux.php';
  require_once 'elements/bandeau_entete.php';
  require_once 'elements/pied_page.php';
  
  // ---------------------------------------------------------------------------
  // --- Definition de la classe Page_France2018

/**
 * @author Pierre Chevaillier
 */
class Page_Test extends Page {
  
  public function __construct($nom_page) {
    parent::__construct($nom_page);
    $this->javascripts[] = "scripts/menu_controleur.js";
    $this->javascripts[] = "scripts/compte_rebours.js";
  }
  
  /**
   * Definit les elments de la page
   */
  protected function definir_elements() {
    $this->elements_haut[] = new Bandeau_Entete();
    $this->elements_haut[] = new Menu_Principal();
    //$this->elements_haut[] = new Entete_Image("media/entetes/banniere_france2018.jpg");
    
    //$this->elements_bas[] = new Bandeau_Partenaires(get_include_path() . "media/logos");
    
    //$b = new Bandeau_Reseaux(get_include_path() . "media/logos");
    //$b->lien_facebook = "http://www.facebook.com/AvironMerPlougonvelin2018";
    //$b->lien_twitter = "http://www.twitter.com/AvironMer29217";
    //$this->elements_bas[] = $b;
    
    $this->elements_bas[] = new Pied_Page();
    
  }
}
// ========================================================================
