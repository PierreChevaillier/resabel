<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classe Club
  // copyright (c) 2018 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : 
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 19-oct-2018 pchevaillier@gmail.com
  // revision : 08-dec-2018 pchevaillier@gmail.com
  // --------------------------------------------------------------------------
  // commentaires :
  // attention :
  // - 
  // a faire :
  // -
  // ==========================================================================

  class Club {
    private $code = 0;
    public function code() { return $this->code; }
    public function def_code($valeur) { $this->code = $valeur;}
    
    public $identifiant = "";
    public function sigle() { return $this->identifiant; }
    
    private $nom = ""; // utf8
    public function nom() { return $this->nom; }
    public function def_nom($valeur) { $this->code = $valeur; }
    
    private $mot_passe = '';
    
    public $lieu_principal_activite = null; // Site_Activite
    public $site_web_principal = null; // Site_Web
    
    public function __construct($code) {
      $this->code = $code;
    }
    
  }
  
  // ==========================================================================
?>
