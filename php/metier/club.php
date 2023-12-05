<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classe Club
  // copyright (c) 2018-2023 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : 
  // utilise avec :
  // - depuis 2023 :
  // PHP 8.2 sur macOS 13.x
  // PHP 8.1 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 19-oct-2018 pchevaillier@gmail.com
  // revision : 08-dec-2018 pchevaillier@gmail.com
  // revision : 03-dec-2023 pchevaillier@gmail.com php 8.x + verifier_mot_passe
  // --------------------------------------------------------------------------
  // commentaires :
  // - incomplet
  // attention :
  // - 
  // a faire :
  // -
  // ==========================================================================

  class Club {
    private int $code = 0;
    public function code(): int { return $this->code; }
    public function def_code(int $valeur): void { $this->code = $valeur;}
    
    public $identifiant = "";
    public function identifiant(): string { return $this->identifiant; }
    public function def_identifiant(string $valeur): void {
      $this->identifiant = $valeur;
    }
    public function sigle(): string { return $this->identifiant; }
    
    private string $nom = ""; // utf8
    public function nom(): string { return $this->nom; }
    public function def_nom(string $valeur): void { $this->nom = $valeur; }
    
    private $fuseau_horaire = null;
    public function def_fuseau_horaire(string $nom_fuseau): void {
      $this->fuseau_horaire = new DateTimeZone($nom_fuseau);
    }
    public function fuseau_horaire(): DateTimeZone { return $this->fuseau_horaire; }
        
    public $lieu_principal_activite = null; // Site_Activite
    public $site_web_principal = null; // Site_Web
    
    public function __construct(int $code) {
      $this->code = $code;
    }
    
    public static function verifier_mot_passe(string $mdp_clair,
                                              string $mdp_hache): bool {
      return password_verify($mdp_clair, $mdp_hache);
    }
    
  }
  
// ============================================================================
?>
