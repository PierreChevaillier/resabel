<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : Classe Seance Activite et associees - Vue metier
  // copyright (c) 2018-2020 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : 
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 09-jun-2019 pchevaillier@gmail.com
  // revision : 18-jan-2020 pchevaillier@gmail.com
  // revision : 08-mar-2020 pchevaillier@gmail.com a_comme_responsable
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // -
  // a faire :
  // -
  // ==========================================================================

  require_once 'php/metier/calendrier.php';
  
  // ==========================================================================
  class Seance_activite {
    
    public $site;
    
    public $support;
    public function code_support() { return $this->support->code(); }
    
    public $code = 0;
    public function code() { return $this->code; }
    public function def_code($code) { $this->code = $code; }
    
    public $plage_horaire;
    public function debut() {
      return $this->plage_horaire->debut();
    }
    public function fin() {
      return $this->plage_horaire->fin();
    }
    
    //$intervalle_realise;
    
    public function responsable_requis() {
      return $this->support->type->chef_de_bord_requis;
    }
    
    public $responsable = null; // si sortie en mer :  resp = chef de bord
    public function a_un_responsable() {
      return (!is_null($this->responsable));
    }
    
    public $inscriptions = array();
    public $etat = '';
        
    public function creer_participation(Membre $personne,
                                        bool $est_responsable) {
      $participation = new Participation_Activite($this, $personne);
      $this->inscriptions[] = $participation;
      if ($est_responsable) $this->responsable = $personne;
      return $participation;
    }
    
    public function definir_horaire(Instant $debut, Instant $fin) {
      $this->plage_horaire = new Intervalle_Temporel($debut, $fin);
    }
    
    public function a_comme_responsable($personne) {
      return ($this->a_un_responsable() && ($this->responsable->code() == $personne->code()));
    }
    
    public function nombre_participants() {
      return count($this->inscriptions);
    }
    
    public function nombre_places_disponibles() {
      // TODO  - attention a la gestion du responsable dans decompte
      // capacite du support - nombre de participants
      return $this->support->capacite() - $this->nombre_participants();
    }
    
  }
  
  // classes : sortie en mer, seance ergo, regate, randonnee, seance_stage...
  // analyser ce que ca change et prvilegier composition a dervivation
  // genre condition, parametres (maree, vent, place ...)
  
  // --------------------------------------------------------------------------
  class Participation_Activite {
    
    public function __construct(Seance_Activite $seance,
                                Membre $personne) {
      $this->seance = $seance;
      $this->participant = $personne;
    }
    
    private $seance = null;
    public $participant = null;
    public $informations = "";
    //$programme = "";
    //$forme = "";
    //$condition_pratique = "";
  }
  // ==========================================================================
?>
