<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServation de Bateaux En Ligne
  // description: definition de la classe Membre
  // utilisation : php - require_once
  // teste avec :
  //  - PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur serveur OVH
  //  - PHP 8.2 sur macOS 13.1 (> 25-dec-2022)
  // Copyright (c) 2014-2024 AMP. Tous droits reserves.
  // ------------------------------------------------------------------------
  // creation: 28-fev-2015 pchevaillier@gmail.com
  // revision: 29-avr-2015 pchevaillier@gmail.com, recherche information
  // revision: 17-aug-2016 pchevaillier@gmail.com, nouvel structure table membre
  // revision: 19-nov-2016 pchevaillier@gmail.com, ajout recherche_membres
  // revision: 30-nov-2016 pchevaillier@gmail.com, test si de permanence
  // revision: 05-oct-2018 pchevaillier@gmail.com  chemin vers utilitaires
  // revision: 06-ami-2019 pchevaillier@gmail.com  initialiser_debutant
  // revision: 29-dec-2022 pchevaillier@gmail.com fix erreur 8.2
  // revision: 30-nov-2023 pchevaillier@gmail.com separation membre - connexion
// revision: 22-may-2024 pchevaillier@gmail.com - enregistrer_nouveau()
  // ------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // - lever une erreur si donnee manquante (chef de bord)
  // -----------------------------------------------------------------------

  require_once 'php/metier/personne.php';
require_once 'php/metier/connexion.php';
require_once 'php/metier/calendrier.php';

  class Membre extends Personne {
    
    const NIVEAU_DEBUTANT = 1;
    
    private $connexion = null;
    public function def_connexion(Connexion $cnx) { $this->connexion = $cnx; }
    
    // private $est_actif = true; // actif = possibilite de pratiquer une activite
    //private $est_autorise_connecter = true;
    public int $niveau = 0;
    public function niveau(): int { return $this->niveau; }
    public function def_niveau(int $valeur) {$this->niveau = $valeur; }
    
    public ? Instant $date_naissance = null; //"0000-00-00";
    private $est_chef_de_bord = false;
    //public ? Instant $date_derniere_connexion = null; //"0000-00-00 00:00:00";
    public $type_licence = "A";
    public $num_licence = "";
	
    public function __construct(int $code) {
      parent::__construct($code);
      $this->connexion = new Connexion();
    }
    
    public function def_chef_de_bord(int $valeur) {  // pas un 'setter' classique
      if ($valeur != 0 && $valeur != 1)
        throw new InvalidArgumentException();
      $this->est_chef_de_bord = ($valeur == 1);
    }
    public function est_chef_de_bord(): bool { return $this->est_chef_de_bord; }
    public function def_est_chef_de_bord(bool $valeur) { $this->est_chef_de_bord = $valeur; }
    
    /**
     * Identifiant de connexion
     * Attribut derive : facade pour masquer l'implementation des informations de connexion
     */
    public function identifiant(): string { return $this->connexion->identifiant(); }
    public function def_identifiant(string $valeur): void {$this->connexion->def_identifiant($valeur);}

    /**
     * Attribut derive : facade pour masquer l'implementation des informations de connexion
     */
    public function est_actif(): bool {
      return $this->connexion->est_compte_actif();
    }
    
    public function def_actif(int $valeur): void { // pas un 'setter' classique
      if ($valeur != 0 && $valeur != 1)
        throw new InvalidArgumentException();
      $this->connexion->def_est_compte_actif($valeur);
    }
    
    /**
     * Attribut derive : facade pour masquer l'implementation des informations de connexion
     */
    
    public function est_autorise_connecter(): bool {
      return $this->connexion->est_autorise();
    }
    
    
    public function def_autorise_connecter(int $valeur): void {  // pas un 'setter' classique
      if ($valeur != 0 && $valeur != 1)
        throw new InvalidArgumentException();
      $this->connexion->def_est_autorise($valeur);
    }
    
    public function est_debutant(): bool {
      return ($this->niveau < self::NIVEAU_DEBUTANT  + 1);
    }
    
    public function initialiser_debutant(): void {
      $this->connexion->def_est_compte_actif(1);
      $this->connexion->def_est_autorise(1);
      $this->est_chef_de_bord = false;
      $this->niveau = self::NIVEAU_DEBUTANT;
    }
      
		
    public function initialiser_visiteur(): void {
      $this->connexion->def_est_compte_actif(1);
      $this->connexion->def_est_autorise(0);
      
      $this->est_chef_de_bord = false;
      $this->niveau = 0;
      $this->prenom = "z";
      $this->code_commune = 29190; // Plougonvelin
      $this->genre = "F";
      return;
    }

  }
// ============================================================================
  ?>
