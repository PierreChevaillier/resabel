<?php
// ========================================================================
// description : definition de la classe Site_web
//               identification (mentions legagles), localisation
// utilisation : Definition des parametres generaux du site web
// teste avec  : PHP 5.5.3 sur Mac OS 10.11
// contexte    : Elements generique d'un site web
// Copyright (c) 2017-2018 AMP
// ------------------------------------------------------------------------
// creation : 22-jul-2017 pchevaillier@gmail.com depuis amp_france2018/site.php
// revision : 16-dec-2018 pchevaillier@gmail.com proprietes plus en dur dans le code
// ------------------------------------------------------------------------
// commentaires :
// - singleton car unique et utilise par de nombreuses classes d'objet
// attention :
// a faire :
// - tests
// ------------------------------------------------------------------------

// --- Classes utilisees

// ------------------------------------------------------------------------
// --- Definition de la classe Site_web

class Site_web {

  private $code = 0;
  public function code() { return $this->code; }
  public function def_code($valeur) { $this->code = $valeur; }
  
  // Permet de mettre le sigle du club dans des elements de page,
  // tel que le titre de la page, le lien 'brand' du menu ...
  private $sigle = "";
  public function sigle() { return $this->sigle; }
  public function def_sigle($valeur) { $this->sigle = $valeur; }
  
  /*
  private $sigle_proprietaire = "AMP";
  public static function sigle_proprietaire() { return Site_web::$instance->sigle_proprietaire; }
  public static function copyright() { return Site_web::$instance->sigle_proprietaire; }
  
  private $nom_proprietaire = "Aviron de Mer de Plougonvelin";
  public static function nom_proprietaire() { return Site_web::$instance->nom_proprietaire; }
  
  private $adresses = array();
  public static function adresses() { return Site_web::$instance->adresses; }
  
  public $telephones = array();
  public static function telephones() { return Site_web::$instance->telephones; }
*/
  
  // permet de mettre un lien vers le site web du club dans un element de page web
  // par exemple dans le menu
  private $adresse_racine = ""; // p. ex. https://site_du_club.com
  public function adresse_racine() { return $this->adresse_racine; }
  public function def_adresse_racine($valeur) { $this->adresse_racine = $valeur; }
  
  private $courriel_contact = "";
  public function courriel_contact() { return  $this->courriel_contact; }
  public function def_courriel_contact($valeur) { $this->courriel_contact = $valeur; }
  
  /*
  private $directeur_publication = "Joël Champeau, président de l’association";
  public static function directeur_publication() { return  Site_web::$instance->directeur_publication; }
  
  private $redaction = "Pierre Chevaillier";
  public static function redaction() { return  Site_web::$instance->redaction; }
  
  private $hebergeur = "OVH, France";
  public static function hebergeur() { return  Site_web::$instance->hebergeur; }
  */
  
  // Information indispensable pour la manipulation des dates et du temps
  private $fuseau_horaire = "Europe/Paris";
  public function fuseau_horaire() { return  $this->fuseau_horaire; }
  public function def_fuseau_horaire($valeur) {
    $this->fuseau_horaire = $valeur;
    date_default_timezone_set($valeur);
  }
  /*
  public $latitude = 48.347;
  public $longitude = -4.704;
  public $elevation = 6.0;
  */
  private static $instance;
  public static function accede() { return self::$instance; }
  
  public function __construct($code) {
    $this->code = $code;
    self::$instance = $this;
  }
  
  public function initialiser() {
    // Fuseau horaire (necessaire pour utiliser la fonction date
    date_default_timezone_set($this->fuseau_horaire);
    /*
    $this->adresses[] = "4 boulevard de la Mer";
    $this->adresses[] = "29217 Plougonvelin, France";
    $this->telephones[] = "+33 2 98 48 27 41";
    $this->telephones[] = "+33 6 82 74 71 22";
    */
  }
  
}
// ========================================================================
