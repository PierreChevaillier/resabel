<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classes definissant la structure organisationnelle du club
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : 
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 23-mai-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // ==========================================================================

  //require_once 'php/metier/membre.php';
  
  // --------------------------------------------------------------------------
  
  class Composante {
    private $code = 0;
    public function code() { return $this->code; }
    public function def_code($valeur) { $this->code = $valeur;}

    private $genre = ""; // pour accord (LE bureau, LA commission...)
    public function genre() { return $this->genre; }
    public function def_genre($valeur) { $this->genre = $valeur; }

    private $nom = ""; // utf8
    public function nom() { return $this->nom; }
    public function def_nom($valeur) { $this->nom = $valeur; }

    private $nom_court = ""; // utf8
    public function nom_court() { return $this->nom_court; }
    public function def_nom_court($valeur) { $this->nom_court = $valeur; }

    private $courriel_contact = "";
    public function courriel_contact() { return $this->courriel_contact; }
    public function def_courriel_contact($valeur) { $this->courriel_contact = $valeur; }

    private $liste_diffusion = "";
    public function liste_diffusion() { return $this->liste_diffusion; }
    public function def_liste_diffusion($valeur) { $this->liste_diffusion = $valeur; }

    public function __construct($code) {
      $this->code = $code;
    }
    
  }
  
  class Role {
    private $code = 0;
    public $nom_masculin = "";
    public $nom_feminin = "";
    public function __construct($code) {
      $this->code = $code;
    }

  }
  
  class Role_Composante {
    public $composante = null;
    public $role = null;
    public $rang_role = 1;
    public $role_principal = false;
  }
  
  class Role_Membre {
    public $membre = null;
    public $role_composante = null;
    
    public function composante() { return $this->role_composante->composante; }
    public function role() { return $this->role_composante->role; }
    public function personne() { return $this->membre; }
  }
  
  // ==========================================================================
?>
