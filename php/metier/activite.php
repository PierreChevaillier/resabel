<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : Classes pour information et planification activite journaliere
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

  class Activite_Journaliere {
    $jour = null;
    $site = null;
    $permanance = null;
    $marees = array();
    $supports_actifs = array; // cle : code_support ; valeur : support activite
    $personnes_actives = array(); // cle : code_personne ; valeur : personne
    
    // Supports disponibles
    $seances_supports = array(); // cle : code_support ; valeurs : seances programmes
    $seances_creneaux = array(); // cle : creneau horaire ; valeurs : seances programmes
  
    $seances_personnes = array();
    $fermeture_site = null;
    $indisponibilites_support_jour = array(); // cle : code_support ; valeurs : indispos sur toute la journee
    $indisponibilites_support_creneaux = array(); // cle : creneau horaire ; valeurs : indispos sopport sur le creneau

  }
  
  /*
  class Plan_Journalier_activite extends Activite_Journaliere {
    $fermeture_site = null;
    $indisponibilites_support_jour = array();
    $indisponibilites_support_creneaux = array();
    
  }
   */
  
  // --------------------------------------------------------------------------
  class Type_Activite {
    $duree = 60; // minutes
    $horaire_debut_minutes = 0; // aux heures 'rondes'
    $decalage_debut_hiver_minutes = 30; // cas AMP pour sorties en mer
    
    private $code = 0;
    public function code() { return $this->code; }
    public function def_code($valeur) { $this->code = $valeur;}
    
    private $nom = ""; // utf8
    public function nom() { return $this->nom; }
    public function def_nom($valeur) { $this->code = $valeur; }
    
    public function __construct($code) { $this->code = $code; }
  }
  
  // ==========================================================================
?>
