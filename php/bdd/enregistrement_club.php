<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classe Enregistrement_Club : interface table 'Club'
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : Classe Club et Base_Donnees
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 19-oct-2018 pchevaillier@gmail.com
  // revision : 09-dec-2018 pchevaillier@gmail.com
  // revision : 11-mar-2019 pchevaillier@gmail.com logique de verifier_identite
  // --------------------------------------------------------------------------
  // commentaires :
  // - en chantier
  // - lire, ajouter, modifier, supprimer, tester_exist, compter, collecter, verfier_xxx
  // - utilise des requetes preparees (http://php.net/manual/fr/pdostatement.bindparam.php)
  // attention :
  // - 
  // a faire :
  // ==========================================================================

  require_once 'php/metier/club.php';
  
  class Erreur_Club_Introuvable extends Exception { }
  class Erreur_Identifiant_Club extends Exception { }
  class Erreur_Mot_Passe_Club extends Exception { }
  
  // ==========================================================================
  class Enregistrement_Club {
    
    static function source(): string {
      return Base_Donnees::$prefix_table . 'club';
    }
    
    private $club = null;
    public function club(): ? Club { return $this->club; }
    public function def_club(Club $club) { $this->club = $club; }
    
    public function verifier_identite(string $mdp_clair): bool {
      $identification_ok = false;
      $bdd = Base_Donnees::acces();
      $code_club = $this->club()->code();
      $identifiant= $this->club->identifiant();
      
      $requete= $bdd->prepare("SELECT identifiant, mot_passe, nom FROM " . self::source() . " WHERE code = :code LIMIT 1");
      $requete->bindParam(':code', $code_club, PDO::PARAM_INT);
      try {
//        $resultat = $bdd->query($requete);
        $requete->execute();
        if ($club = $requete->fetch(PDO::FETCH_OBJ)) {//($resultat->rowCount() > 0) {
          $mdp_ok = Club::verifier_mot_passe($mdp_clair, $club->mot_passe);
          
          if (strtolower($club->identifiant) != strtolower($identifiant)) {
            throw new Erreur_Identifiant_Club();
            $identification_ok = false; //return $identification_ok;
          } else if (!$mdp_ok) {
            throw new Erreur_Mot_Passe_Club();
            $identification_ok = false; //return $identification_ok;
          } else {
            $this->club->def_nom($club->nom);
            $identification_ok = true;
          }
        } else {
          throw new Erreur_Club_introuvable();
          $identification_ok = false; //return $identification_ok;
        }
          //$resultat->closeCursor();
        //return $identification_ok;
      } catch (PDOException  $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $identification_ok;
    }
    
    static public function tester_existe(string $identifiant): bool {
      $existe = false;
      try {
        $bdd = Base_Donnees::acces();
        $requete= $bdd->prepare("SELECT COUNT(*) AS n FROM " . self::source() . " WHERE identifiant = :identifiant");
        $requete->bindParam(':identifiant', $identifiant, PDO::PARAM_STR);
        $requete->execute();
        if ($resultat = $requete->fetch(PDO::FETCH_OBJ))
          $existe = ($resultat->n == 1);
      } catch (PDOException  $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $existe;
    }
    
    public function lire(): bool {
      // TODO : completer avec tous les champs
      try {
        $bdd = Base_Donnees::acces();
        $requete = $bdd->prepare("SELECT nom, identifiant, fuseau_horaire FROM " . self::source() . " WHERE code = :code LIMIT 1");
        $code_club = $this->club()->code();
        $requete->bindParam(':code', $code_club, PDO::PARAM_INT);
        $requete->execute();
        if ($donnee = $requete->fetch(PDO::FETCH_OBJ)) {
          $this->initialiser_depuis_table($donnee);
        } else {
          return false;
        }
      } catch (PDOexception $e) {
        die("Erreur recherche dans " . self::source() . " avec " . $critere . " : ligne " . $e->getLine() . ' :<br /> ' . $e->getMessage());
      }
      $requete->closeCursor();
      return true;
    }
    
    private function initialiser_depuis_table($donnee): void {
      $this->club->def_identifiant($donnee->identifiant);
      $this->club->def_nom($donnee->nom);
      $this->club->def_fuseau_horaire($donnee->fuseau_horaire);
    }
  }
  // ==========================================================================
?>
