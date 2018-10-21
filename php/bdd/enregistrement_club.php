<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classe Enregistrement_Club : interface base donnees
  // copyright (c) 2018 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : Classe Club et Base_Donnees
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 19-oct-2018 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // - lire, ajouter, modifier, supprimer, existe, nombre, collecter, verfier_xxx
  // attention :
  // - 
  // a faire :
  // -
  // ==========================================================================

  require_once 'php/metier/club.php';
  
  // ==========================================================================
  class Enregistrement_Club {
    static function source() {
      return Base_Donnees::$prefix_table . 'club';
    }
    
    private $club = null;
    public function club() { return $this->club; }
    public function def_club($club) { $this->club = $club; }
    
    static public function champs_recherche() {
      return 'nom';
    }
    
    private function critere_recherche() {
      $bdd = Base_Donnees::accede();
      return "code = " . $bdd->quote($this->club->sigle()) ;
    }
    
    public function lire() {
      $critere = $this->critere_recherche();
      
      $requete = "SELECT " . self::champs_recherche() . " FROM " . self::source() . " WHERE " . $critere;
      try {
        $bdd = Base_Donnees::accede();
        $resultat = $bdd->query($requete);
        $donnee = $resultat->fetch();
        $this->initialiser_depuis_table($donnee);
      } catch (PDOexception $e) {
        die("Erreur recherche club par " . $critere . " : ligne " . $e->getLine() . ' :</b> '. $e->getMessage());
      }
      $resultat->closeCursor();
    }
    
    private function initialiser_depuis_table($donnee) {
      $this->club->def_nom($donnee['nom']);
    }
  }
  // ==========================================================================
?>
