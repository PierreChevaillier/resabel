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
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // -
  // a faire :
  // -
  // ==========================================================================

  class Seance_activite {
    $site;
    $support;
    $intervalle_programme;
    //$intervalle_realise;
    public function responsable_requis() {
      return $this->support->type->$chef_de_bord_requis;
    }
    
    $responsable = null; // si sortie en mer :  resp = chef de bord
    
    $inscriptions = array();
    $etat = '';
    
    public function __construct(boolean $requiert_responsable = false) {
      $this->requiert_responsable = $requiert_responsable;
    }
    
    public function ajouter_participant(Membre $personne, boolean $est_responsable = false) {
      $inscription = new Participation_Activite($this, $personne);
      $this->inscription[] = $inscription;
      if ($est_responable) $this->responsable = $personne;
    }
    
    public function nombre_participants() { return count($this->inscriptions); }
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
    
    $seance = null;
    $participant = null;
    $informations = "";
    $programme = "";
    $forme = "";
    $condition_pratique = "";
  }
  // ==========================================================================
?>
