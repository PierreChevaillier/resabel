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
  abstract class Site_Activite {
    
    public $longitude = 0.0;
    public $latitude = 0.0;
    
    public $supports_activite = null; // array
    private $ouvert = true;
    
    private $code_regime_ouverture = 0;
    public function code_regime_ouverture() { return $this->code_regime_ouverture; }
    public function def_code_regime_ouverture($valeur) { $this->code_regime_ouverture = $valeur;}
    
    public $regime_ouverture;
    
    private $code = 0;
    public function code() { return $this->code; }
    public function def_code($valeur) { $this->code = $valeur;}
    
    private $nom = ""; // utf8
    public function nom() { return $this->nom; }
    public function def_nom($valeur) { $this->nom = $valeur; }
    
    private $nom_court = ""; // utf8
    public function nom_court() { return $this->nom; }
    public function def_nom_court($valeur) { $this->nom_court = $valeur; }
    
    public function __construct($code) { $this->code = $code; }
    
    public abstract function est_creneau_possible($intervalle_temporel);
  }
    
  // --------------------------------------------------------------------------
  class Site_Activite_Mer extends Site_Activite {
    public $marees = array(); // on avant charge le tableau des marees
    
    public $hauteur_maree_min;
    public $hauteur_maree_max;
    
    public function est_creneau_possible($intervalle_temporel) {
      $possible = true;
      // verifier contraintes marees
      return $possible;
    }
  }
  
  // --------------------------------------------------------------------------
  class Salle_Sport extends Site_Activite {
    
    public function est_creneau_possible($intervalle_temporel) {
      $possible = true;
      // verifier regime_ouverture
      return $possible;
    }
  }
  // ==========================================================================
?>
