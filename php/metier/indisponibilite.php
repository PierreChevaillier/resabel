<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classes Indisponibilite (site d'activite ou support activite)
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
  class Indisponibilite {
    public $periode = null;
    public $motif;
 
    public $createur; // personne ou annonyme (code = 0)
    public $instant_creation;
    
    private $information = "";
    public function information() { return $this->information; }
    public function def_information($valeur) { $this->information = $valeur; }
    
    private $code = 0;
    public function code() { return $this->code; }
    public function def_code($valeur) { $this->code = $valeur;}
    
    public function __construct($code) { $this->code = $code; }
    
    public function formatter_periode() {
      $code_html = "";
      $cal = calendrier::obtenir();
      $code_html = "du " . $cal->date_texte_court($this->periode->debut()) . " " . $cal->heures_minutes_texte($this->periode->debut());
      $code_html = $code_html . " au " . $cal->date_texte_court($this->periode->fin()) . " " . $cal->heures_minutes_texte($this->periode->fin());
      return $code_html;
    }
    
  }
  
  // --------------------------------------------------------------------------
  class Fermeture_Site extends Indisponibilite {
    public $site_activite = null;
  }
  
  class Indisponibilite_Support extends Indisponibilite {
    public $support = null;
  }
  
  // --------------------------------------------------------------------------
  class Motif_Indisponibilite {
    public $composante_gestionnaire = null; // hors administration resabel
    private $code = 0;
    public function code() { return $this->code; }
    public function def_code($valeur) { $this->code = $valeur;}
    
    private $nom = ""; // utf8
    public function nom() { return $this->nom; }
    public function def_nom($valeur) { $this->nom = $valeur; }
    
    public function __construct($code) { $this->code = $code; }
  }
  
  // ==========================================================================
?>
