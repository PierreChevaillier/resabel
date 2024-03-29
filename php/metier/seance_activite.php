<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : Classe Seance Activite et associees - Vue metier
  // copyright (c) 2018-2023 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : 
  // utilise avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  //  - depuis 25-dec-2022 :
  //    PHP 8.2 sur macOS 13.1
  // --------------------------------------------------------------------------
  // creation : 09-jun-2019 pchevaillier@gmail.com
  // revision : 18-jan-2020 pchevaillier@gmail.com
  // revision : 08-mar-2020 pchevaillier@gmail.com a_comme_responsable
  // revision : 29-dec-2022 pchevaillier@gmail.com information
  // revision : 29-dec-2022 pchevaillier@gmail.com MaJ suite tests unitaires
  // --------------------------------------------------------------------------
  // commentaires :
  // - version minimale / pratique AMP
  // attention :
  // -
  // a faire :
  // -
  // ==========================================================================

  require_once 'php/metier/support_activite.php';
  require_once 'php/metier/membre.php';
  require_once 'php/metier/calendrier.php';
  
  // ==========================================================================
  class Seance_activite {
    
    public $site;
    
    public ?Support_Activite $support = null;
    public function def_support(Support_Activite $support): void {
      $this->support = $support;
    }
    public function code_support(): int {
      return $this->support->code();
    }
    
    public $code = 0;
    public function code(): int { return $this->code; }
    public function def_code(int $code): void { $this->code = $code; }
    
    public $plage_horaire;
    public function debut(): ?Instant {
      return $this->plage_horaire->debut();
    }
    public function fin(): ?Instant {
      return $this->plage_horaire->fin();
    }
    
    public $information = "";
    
    //$intervalle_realise;
    
    public function responsable_requis(): bool {
      return $this->support->type->responsable_requis();
    }
    
    public ?Membre $responsable = null; // si sortie en mer :  resp = chef de bord
    
    
    public function a_un_responsable(): bool {
      return (!is_null($this->responsable));
    }
    
    public $inscriptions = array();
    public $etat = '';
        
    public function creer_participation(Membre $personne,
                                        bool $est_responsable = false): ?Participation_Activite {
      if ($est_responsable) $this->responsable = $personne; // Attention!
      foreach ($this->inscriptions as $p) {
        if (!is_null($p->participant) && $p->participant->code() == $personne->code()) {
          return $p;
        }
      }
      $participation = null;
      $pas_possible = !is_null($this->support) && $this->nombre_places_est_limite() && $this->nombre_places_disponibles() <= 0;
      if (!$pas_possible) {
        $participation = new Participation_Activite($this, $personne);
        $this->inscriptions[] = $participation;
      }
      return $participation;
    }
    
    public function definir_horaire(Instant $debut, Instant $fin): void {
      $this->plage_horaire = new Intervalle_Temporel($debut, $fin);
    }
    
    public function a_comme_responsable(Membre $personne): bool {
      return ($this->a_un_responsable() && ($this->responsable->code() == $personne->code()));
    }
    
    public function changer_responsable(Membre $personne): bool {
      if ($this->a_comme_participant($personne)) {
        $this->responsable = $personne;
        return true;
      } else {
        return false;
      }
    }

    public function annuler_responsable(): void {
      $this->responsable = null;
    }
    
    public function nombre_participants(): int {
      return count($this->inscriptions);
    }
    
    public function nombre_places_est_limite(): bool {
      $condition = !is_null($this->support->capacite());
      return $condition;
    }
    
    public function nombre_places_disponibles(): ?int {
      // capacite du support - nombre de participants
      $resultat = null;
      if ($this->nombre_places_est_limite())
        $resultat = $this->support->capacite() - $this->nombre_participants();
      return $resultat; 
    }
    
    public function a_comme_participant(Membre $personne): bool {
      $resultat = false;
      foreach ($this->inscriptions as $p) {
        if (!is_null($p->participant) && $p->participant->code() == $personne->code())
          return true;
      }
      return $resultat;
    }
  }
  
  // classes : sortie en mer, seance ergo, regate, randonnee, seance_stage...
  // analyser ce que ca change et privilegier composition a derivation
  // genre condition, parametres (maree, vent, place ...)
  
  // --------------------------------------------------------------------------
  class Participation_Activite {
    
    public function __construct(Seance_Activite $seance,
                                Membre $personne) {
      $this->seance = $seance;
      $this->participant = $personne;
    }
    
    private $seance; //TODO: a verifier apres test Enregistrement_Activite
    public Membre $participant;
    public $information = "";
    //$programme = "";
    //$forme = "";
    //$condition_pratique = "";
  }
  // ==========================================================================
?>
