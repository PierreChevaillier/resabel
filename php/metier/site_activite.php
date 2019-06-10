<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classes supportant les informations sur sites d'acitivite
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : 
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 09-jun-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // -
  // a faire :
  // -
  // ==========================================================================

  // --------------------------------------------------------------------------
  require_once 'php/metier/calendrier.php';
  
  // --------------------------------------------------------------------------
  class Site_Activite {
    
    private $code = 0;
    public function code() { return $this->code; }
    public function def_code($valeur) { $this->code = $valeur;}
    
    private $nom = ""; // utf8
    public function nom() { return $this->nom; }
    public function def_nom($valeur) { $this->nom = $valeur; }
    
    public $supports_activite = array();
    private $ouvert = true;
    
    private $longitude;
    private $latitude;
    private $time_zone;
    
    public function __construct($code) { $this->code = $code; }
    
    public function est_creneau_possible($intervalle_temporel) {
      return true;
    }
    
  }
    
  // --------------------------------------------------------------------------
  class Site_Activite_Mer extends Site_Activite {
    public $marees = array(); // on avant charge le tableau des marees
    public $hauteur_maree_min;
    public $hauteur_maree_max;
    
    public function est_creneau_possible($intervalle_temporel) {
      $possible = true;
      // verifier heures levée- coucher soleil
      // verifier contraintes marees
        return $possible;
    }
  }
  
  // ==========================================================================
?>
