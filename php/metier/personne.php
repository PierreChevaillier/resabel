<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServation de Bateaux En Ligne
  // description: definition de la classe Personne
  // utilisation : php - require_once
  // teste avec : PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur serveur OVH
  // Copyright (c) 2014-2018 AMP. Tous droits reserves.
  // ------------------------------------------------------------------------
  // creation: 28-fev-2015 pchevaillier@gmail.com
  // revision: 29-avr-2015 pchevaillier@gmail.com, recherche information
  // revision: 17-aug-2016 pchevaillier@gmail.com, nouvel structure table membre
  // revision: 19-nov-2016 pchevaillier@gmail.com, ajout recherche_membres
  // revision: 30-nov-2016 pchevaillier@gmail.com, test si de permanence
  // revision: 05-oct-2018 pchevaillier@gmail.com chemin vers utilitaires
  // revision: 03-mar-2019 pchevaillier@gmail.com ajout nom_commune
  // revision: 18-nov-2023 pchevaillier@gmail.com suppr. vieux code, accesseurs code
  // ------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // - code : entier
  // - revoir les requetes a la base de donnees
  // - lever une erreur si donnee manquante (chef de bord)
  // -----------------------------------------------------------------------

  //require_once 'php/utilitaires/format_donnees.php';

  class Personne {
    protected $code = 0;
    public function def_code(int $valeur): void { $this->code = $valeur; }
    public function code(): int { return $this->code; }
    //public $identifiant = "";
    
    public $genre = "F";
    public function def_genre(string $valeur):void {
      $this->genre = strtoupper($valeur);
    }
    
    public function est_femme(): bool { return ($this->genre == "F"); }
    public function est_homme(): bool { return ($this->genre == "M"); }
    
    public function civilite(): string { return ($this->genre == "F") ? "Mme" : "M."; }
    
    public $prenom = "";
    public function prenom(): string { return $this->prenom; }
    public function def_prenom(string $valeur) { $this->prenom = $valeur; }
    
    public $nom = "";
    public function nom(): string { return $this->nom; }
    public function def_nom(string $valeur): void { $this->nom = $valeur; }
    
    
    public $code_commune = 0;
    public $nom_commune = ""; // pas dans la table
    public $rue = "";
    
    public $telephone = "";
    public function def_telephone(string $valeur): void { $this->telephone = $valeur; }
    
    public $telephone2 = "";
    
    public $courriel = "";
    public function def_courriel(string $valeur): void { $this->courriel = $valeur; }
    
    //public $est_membre_actif = False;
    //public $est_autorise_connecte = False;
    //public $est_nouveau = False;
 
    public function __construct(int $code) {
      $this->code = $code;
    }

  }
  ?>
