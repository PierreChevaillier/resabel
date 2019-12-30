<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classes Indisponibilite (site d'activite ou support activite)
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : 
  // teste avec : PHP 7.1 sur MacOS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 09-jun-2019 pchevaillier@gmail.com
  // revision : 29-jun-2019 pchevaillier@gmail.com
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
    public $debut = null;
    public $fin = null;
    public $motif;
 
    public $createur; // personne ou anonyme (code = 0)
    public $instant_creation = null;
    
    private $information = "";
    public function information() { return $this->information; }
    public function def_information(string $valeur) { $this->information = $valeur; }
    
    private $code = 0;
    public function code() { return $this->code; }
    public function def_code($valeur) { $this->code = $valeur;}
    
    public function __construct($code) { $this->code = $code; }
    
    public function formatter_periode() {
      $code_html = "du " . $this->debut->date_texte_court() . " à " . $this->debut->heure_texte();
      $code_html = $code_html . " au " . $this->fin->date_texte_court() . " à " . $this->fin->heure_texte();
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
