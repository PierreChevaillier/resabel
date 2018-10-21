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
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // attention :
  // - 
  // a faire :
  // -
  // ==========================================================================

  class Club {
    private $code = '';
    public function sigle() { return $this->code; }
    
    private $nom = "";
    public function nom() { return $this->nom; }
    public function def_nom($valeur) { $this->code = $valeur; }
    
    public $site_principal_activite = null; // Site_Activite
    public $site_web_principal = null; // Site_Web
    
    public function __construct($code) {
      $this->code = $code;
    }
    
  }
  
  // ==========================================================================
?>
