<?php
// ========================================================================
// description : definition de la classe Site_web
//               identification (mentions legagles), localisation
// utilisation : Definition des parametres generaux du site web
// teste avec  : PHP 5.5.3 sur Mac OS 10.11
// contexte    : Elements generique d'un site web
// Copyright (c) 2017 AMP
// ------------------------------------------------------------------------
// creation : 22-juil-2017 pchevaillier@gmail.com depuis amp_france2018/site.php
// revision :
// ------------------------------------------------------------------------
// commentaires :
// - singleton car unique et utilise par de nombreuses classes d'objet
// attention :
// a faire :
// - charger les informations depuis la bdd
// ------------------------------------------------------------------------

// --- Classes utilisees

// ------------------------------------------------------------------------
// --- Definition de la classe Site_web

class Site_web {

  public $nom = "";
  
  private $sigle_proprietaire = "AMP";
  public static function sigle_proprietaire() { return Site_web::$instance->sigle_proprietaire; }
  public static function copyright() { return Site_web::$instance->sigle_proprietaire; }
  
  private $nom_proprietaire = "Aviron de Mer de Plougonvelin";
  public static function nom_proprietaire() { return Site_web::$instance->nom_proprietaire; }
  
  private $adresses = array();
  public static function adresses() { return Site_web::$instance->adresses; }
  
  public $telephones = array();
  public static function telephones() { return Site_web::$instance->telephones; }

  private $mail_contact = "contact@avironplougonvelin.fr";
  public static function mail_contact() { return Site_web::$instance->mail_contact; }
  
  private $directeur_publication = "Joël Champeau, président de l’association";
  public static function directeur_publication() { return  Site_web::$instance->directeur_publication; }
  
  private $redaction = "Pierre Chevaillier";
  public static function redaction() { return  Site_web::$instance->redaction; }
  
  private $hebergeur = "OVH, France";
  public static function hebergeur() { return  Site_web::$instance->hebergeur; }
  
  public $fuseau_horaire = "Europe/Paris";
  public $latitude = 48.347;
  public $longitude = -4.704;
  public $elevation = 6.0;
  
  public static $instance;
  
  public function __construct($nom_site) {
    $this->nom = $nom_site;
    Site_web::$instance = $this;
    
  }
  
  public function initialiser() {
    // Fuseau horaire (necessaire pour utiliser la fonction date
    date_default_timezone_set($this->fuseau_horaire);
    
    $this->adresses[] = "4 boulevard de la Mer";
    $this->adresses[] = "29217 Plougonvelin, France";
    $this->telephones[] = "+33 2 98 48 27 41";
    $this->telephones[] = "+33 6 82 74 71 22";
    
  }
  
}
// ========================================================================
