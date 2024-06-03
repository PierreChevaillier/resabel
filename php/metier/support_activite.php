<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classes supportant les informations sur supports d'activite
  //               p. ex : yoles, plateaux d'ergos
  // copyright (c) 2018-2024 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : 
  // utilise avec : PHP 7.1 sur Mac OS 10.14 ;
  //                PHP 7.0 sur hebergeur web
  //  - depuis 25-dec-2022 :
  //    PHP 8.2 sur macOS 13.1
  // --------------------------------------------------------------------------
  // creation : 09-jun-2019 pchevaillier@gmail.com
  // revision : 08-sep-2019 pchevaillier@gmail.com identite_texte()
  // revision : 29-dec-2022 pchevaillier@gmail.com MaJ suite tests unitaires
// revision : 26-fev-2024 pchevaillier@gmail.com typage
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // -
  // a faire :
  // -
  // ==========================================================================


  // --------------------------------------------------------------------------
  class Support_Activite {
    //public static $code_type; // a voir...
    public $actif = true;
    public function est_actif(): bool { return $this->actif; }
    
    public ?Type_Support_Activite $type = null;
    public function def_type(Type_Support_Activite $type_support): void {
      $this->type = $type_support;
    }
    
    public $site_base = null; // pourrait etre momentanement ailleurs (a voir)
    
    public $nombre_initiation_min = 0; // ca se definit par support et pas par type
    public $nombre_initiation_max = 0;

    public $pour_loisir = true; // peut etre a la fois pour loisir et competition
    public $pour_competition = true; // pour l'instant pas de distinction au niveau des membres
    
    private $code = 0; // identifiant interne non modifiable
    public function code(): int { return $this->code; }
    public function def_code(int $valeur) { $this->code = $valeur;}
    
    private $numero = ''; // identifiant pour utilisateur
    public function numero(): string { return $this->numero; }
    public function def_numero(?string $valeur) { $this->numero = $valeur;}
    
    private $nom = ""; // utf8
    public function nom(): string { return $this->nom; }
    public function def_nom(string $valeur): void { $this->nom = $valeur; }
    
    public $modele = "";
    public $constructeur = "";
    public $annee_construction = 2020;
    
    public function __construct(int $code) { $this->code = $code; }
    
    public function nom_type() {
        return (is_null($this->type)) ? "Type non défini" : $this->type->nom();
    }
    
    public function capacite(): ?int {
      $resultat = null;
      if (!is_null($this->type))
        $resultat = $this->type->nombre_personnes_max;
      return $resultat;
    }
    
    public function est_pour_loisir() { return $this->pour_loisir; }
    public function est_pour_competition() { return $this->pour_competition; }
    
    public function identite_texte() { return $this->nom() .  ' (' .  $this->nom_type() . ')'; }
    
  }
  
  class Bateau extends Support_Activite {
    // A voir : est-ce utile ? Depend des services
    
    public $immatriculation = "";
    public $categorie_navigation;
        
    public function identite_texte() { return $this->numero() . ' ' . $this->nom() .  ' (' .  $this->nom_type() . ')'; }
    
  }
  
  class Plateau_Ergo extends Support_Activite {
    public $nombre_postes = null; // Peut differe d'un plateau a l'autre, contrairement aux bateaux
    
    public function capacite(): ?int { return $this->nombre_postes; }
  }
  
// ----------------------------------------------------------------------------
  class Type_Support_Activite {
    public $type_site = null; // Type de site auquel est destine ce type de support
    
    public $nombre_personnes_min = null; // pour l'aviron: min = max
    public $nombre_personnes_max = null; // pour ergo, on ne met rien
    
    public $chef_de_bord_requis = false;
    public function requiert_responsable(bool $valeur): void {
      $this->chef_de_bord_requis = $valeur;
    }
    public function responsable_requis(): bool {
      return $this->chef_de_bord_requis;
    }
    
    private $code = 0;
    public function code(): int { return $this->code; }
    public function def_code(int $valeur):void { $this->code = $valeur;}
    
    private $nom = ""; // utf8
    public function nom(): string { return $this->nom; }
    public function def_nom(string $valeur) { $this->nom = $valeur; }
    
    public function __construct(int $code) {
      $this->code = $code;
    }
  }
  
  // ==========================================================================
?>
