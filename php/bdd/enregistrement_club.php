<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classe Enregistrement_Club : interface table 'Club'
  // copyright (c) 2018 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : Classe Club et Base_Donnees
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 19-oct-2018 pchevaillier@gmail.com
  // revision : 09-dec-2018  pchevaillier@gmail.com
  // --------------------------------------------------------------------------
  // commentaires :
  // - en chantier : pas fonctionnel
  // - lire, ajouter, modifier, supprimer, existe, nombre, collecter, verfier_xxx
  // attention :
  // - 
  // a faire :
  // -
  // ==========================================================================

  require_once 'php/metier/club.php';
  
  class Erreur_Club_Introuvable extends Exception { }
  class Erreur_Mot_Passe_Club extends Exception { }
  
  // ==========================================================================
  class Enregistrement_Club {
    
    static function source() {
      return Base_Donnees::$prefix_table . 'club';
    }
    
    private $club = null;
    public function club() { return $this->club; }
    public function def_club($club) { $this->club = $club; }
    
    //static public function champs_recherche() {
    //  return 'nom';
   // }
    
    private function critere_recherche() {
      $bdd = Base_Donnees::accede();
      return "code = " . $bdd->quote($this->club->code()) ;
    }
    
    public function verifier_identite($mot_passe) {
      $identification_ok = false;
      $bdd = Base_Donnees::accede();
      $requete= "SELECT code, mot_passe, nom FROM " . self::source() . " WHERE identifiant = " . $bdd->quote($this->club->identifiant);
      try {
        $resultat = $bdd->query($requete);
        if ($resultat->rowCount() > 0) {
          $club = $resultat->fetch(PDO::FETCH_OBJ);
          if ($club->mot_passe != $mot_passe) {
            throw new Erreur_Mot_Passe_Club();
            return $identification_ok;
          } else {
            $this->club->def_code($club->code);
            $this->club->def_nom(utf8_encode($club->nom));
            $identification_ok = true;
          }
        } else {
          throw new Erreur_Club_introuvable();
          return $identification_ok;
        }
        $resultat->closeCursor();
        return $identification_ok;
      } catch (PDOException  $e) {
        echo "Erreur requete sur la table " . self::source() . " : ligne " . $e->getLine() . " :<br /> " . $e->getMessage();
        exit();
      }
    }
    
    public function lire() {
      $critere = $this->critere_recherche();
      // TODO : completer avec tous les champs
      $requete = "SELECT nom FROM " . self::source() . " WHERE " . $critere;
      try {
        $bdd = Base_Donnees::accede();
        $resultat = $bdd->query($requete);
        $donnee = $resultat->fetch();
        $this->initialiser_depuis_table($donnee);
      } catch (PDOexception $e) {
        die("Erreur recherche dans " . self::source() . " avec " . $critere . " : ligne " . $e->getLine() . ' :<br /> ' . $e->getMessage());
      }
      $resultat->closeCursor();
    }
    
    private function initialiser_depuis_table($donnee) {
      $this->club->def_nom($donnee['nom']);
    }
  }
  // ==========================================================================
?>
