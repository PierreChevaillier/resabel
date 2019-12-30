<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classe Permanence - personne resp. organisation des sorties
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : 
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 28-mai-2019 pchevaillier@gmail.com
  // revision : 28-dec-2019 pchevaillier@gmail.com impact refonte Calendrier
  // --------------------------------------------------------------------------
  // commentaires :
  // attention :
  // - 
  // a faire :
  // -
  // ==========================================================================

  // --------------------------------------------------------------------------
  require_once 'php/metier/calendrier.php';
  require_once 'php/metier/personne.php';
  
  // ==========================================================================
  class Permanence {
    
    public $responsable = null;
    public function responsable() { return $this->responsable; }
    public function def_responsable(Personne $personne) { $this->responsable = $personne;}
    
    private $semaine = 0;
    public function semaine() { return $this->semaine; }
    //public function def_semaine($valeur) { $this->semaine = $valeur; }
    
    private $annee = 0;
    public function annee() { return $this->annee; }
    //public function def_annee($valeur) { $this->annee = $valeur; }
    
    public function code_responsable() { return $this->responsable->code(); }
    
    public function __construct($semaine, $annee) {
      $this->semaine = $semaine;
      $this->annee = $annee;
    }
    /*
     * Creation de la permanence pour le semaine en cours
     */
    public static function cette_semaine(& $permanence) {
      $j = Calendrier::aujourdhui();
      $permanence = new Permanence($j->numero_semaine(), Calendrier::annee_semaine($j));
      return;
    }
    
    public function egale($perm) {
      return (($this->annee == $perm->annee) && ($this->semaine == $perm->semaine));
    }
    
    public function jour() {
      return Calendrier::date_jour_semaine(6, $this->semaine, $this->annee);
    }
    
    public function jour_texte() {
      return $this->jour()->date_texte();
    }
  }
  
  // ==========================================================================
?>
