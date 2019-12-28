<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classes supportant les informations sur supports d'activite
  //               p. ex : yoles, plateaux d'ergos
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : 
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 09-jun-2019 pchevaillier@gmail.com
  // revision : 08-sep-2019 pchevaillier@gmail.com identite_texte()
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
    public static $code_type; // a voir...
    public $actif = true;
    public $type = null;
    public $site_base = null; // pourrait etre momentanement ailleurs (a voir)
    
    public $nombre_initiation_min; // ca se defini par support et pas par type
    public $nombre_initiation_max;

    private $pour_loisir = true; // peut etre a la fois pour loisir et competition
    public $pour_competition = true; // pour l'instant pas de distinction au niveau des membres
    
    private $code = 0;
    public function code() { return $this->code; }
    public function def_code($valeur) { $this->code = $valeur;}
    
    private $nom = ""; // utf8
    public function nom() { return $this->nom; }
    public function def_nom($valeur) { $this->nom = $valeur; }
    
    public function __construct($code) { $this->code = $code; }
    
    public function nom_type() { return $this->type->nom(); }
    public function est_pour_loisir() { return $pour_loisir; }
    public function est_pour_competition() { return $pour_comeptition; }
    
    public function identite_texte() { return $this->nom() .  ' (' .  $this->nom_type() . ')'; }
    
  }
  
  class Bateau extends Support_Activite {
    // A voir : est-ce utile ? Depend des services
    private $numero;
    public function numero() { return $this->numero; }
    public function def_numero($valeur) { $this->numero = $valeur;}
    
    public $immatriculation;
    public $categorie_navigation;
    
    public function identite_texte() { return $this->numero() . ' ' . $this->nom() .  ' (' .  $this->nom_type() . ')'; }
    
  }
  
  class Plateau_Ergo extends Support_Activite {
    public $nombre_postes; // Peut differe d'un plateau a l'autre, contrairement aux bateaux
  }
  
  class Type_Support_Activite {
    public $type_site = null; // Type de site auquel est destine ce type de support
    public $nombre_personnes_min; // pour l'aviron: min = max
    public $nombre_personnes_max; // pour ergo, on ne pet rien
    public $chef_de_bord_requis;
    
    private $code = 0;
    public function code() { return $this->code; }
    public function def_code($valeur) { $this->code = $valeur;}
    
    private $nom = ""; // utf8
    public function nom() { return $this->nom; }
    public function def_nom($valeur) { $this->nom = $valeur; }
    
    public function __construct($code) { $this->code = $code; }
  }
  
  // ==========================================================================
?>
