<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classe Enregistrement_Personne : interface base donnees
  // copyright (c) 2018 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : Classe Pzrsonne et Base_Donnees
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 08-dec-2018 pchevaillier@gmail.com
  // revision : 23-dec-2018  pchevaillier@gmail.com, requetes preparees
  // --------------------------------------------------------------------------
  // commentaires :
  // - en chantier : pas fonctionnel
  // - lire, ajouter, modifier, supprimer, tester_exist, compter, collecter, verfier_xxx
  // attention :
  // - 
  // a faire :
  // -
  // ==========================================================================

  require_once 'php/metier/personne.php';
  
  class Erreur_Personne_Introuvable extends Exception { }
  class Erreur_Mot_Passe_Personne extends Exception { }
  
  // ==========================================================================
  class Enregistrement_Personne {
    static function source() {
      return Base_Donnees::$prefix_table . 'membres';
    }
    
    private $personne = null;
    public function personne() { return $this->personne; }
    public function def_personne($personne) { $this->personne = $personne; }
    
    public function verifier_identite($mot_passe) {
      $identification_ok = false;
      $bdd = Base_Donnees::accede();
      $requete= $bdd->prepare("SELECT code, actif, identifiant, connexion, mot_passe, prenom, nom, cdb FROM " . self::source() . " WHERE identifiant = :identifiant LIMIT 1");
      $requete->bindParam(':identifiant', $this->personne->identifiant, PDO::PARAM_STR);
      try {
        $requete->execute();
        if ($personne = $requete->fetch(PDO::FETCH_OBJ)) {//($resultat->rowCount() > 0) {
          if ($personne->mot_passe != $mot_passe) {
            throw new Erreur_Mot_Passe_Personne();
            return $identification_ok;
          } else {
            $this->personne->def_code($personne->code);
            $this->personne->prenom = utf8_encode($personne->prenom);
            $this->personne->nom = utf8_encode($personne->nom);
            $this->personne->def_autorisee_connecter($personne->connexion);
            $this->personne->def_chef_de_bord($personne->cdb);
            $this->personne->def_active($personne->actif);
            $identification_ok = true;
          }
        } else {
          throw new Erreur_Personne_Introuvable();
          return $identification_ok;
        }
      } catch (PDOException $e) {
       Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      //$resultat->closeCursor();
      return $identification_ok;
    }
    
    /*
     * recherche les informations dans la base de donnees
     */
    public function lire() {
      $trouve = false;
      try {
        $bdd = Base_Donnees::accede();
        $requete= $bdd->prepare("SELECT * FROM " . self::source() . " WHERE code = :code_pers LIMIT 1");
        $requete->bindParam(':code_pers', $this->personne->code(), PDO::PARAM_INT);
        $requete->execute();
        if ($personne = $requete->fetch(PDO::FETCH_OBJ)) {
          $this->initialiser_depuis_table($personne);
          $trouve = true;
        } else {
          throw new Erreur_Personne_Introuvable();
          return $trouve;
        }
      } catch (PDOexception $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $trouve;
    }
    
    private function initialiser_depuis_table($donnee) {
      $this->personne->identifiant = $donnee->identifiant;
      $this->personne->def_active($donnee->actif);
      $this->personne->def_autorisee_connecter($donnee->connexion);
      $this->personne->niveau = $donnee->niveau;
      $this->personne->genre = $donnee->genre;
      $this->personne->prenom = utf8_encode($donnee->prenom);
      $this->personne->nom = utf8_encode($donnee->nom);
      $this->personne->date_naissance = $donnee->date_naissance;
      $this->personne->code_commune = $donnee->code_commune;
      $this->personne->rue = $donnee->rue;
      $this->personne->telephone = $donnee->telephone;
      $this->personne->telephone2 = $donnee->telephone2;
      $this->personne->courriel = $donnee->courriel;
      $this->personne->def_chef_de_bord($donnee->cdb);
      $this->personne->date_derniere_connexion = $donnee->derniere_connexion;
      $this->personne->num_licence = $donnee->num_licence;
    }
    
    public function recherche_si_admin() {
      $est_admin = false;
      // teste si la personne a le role admin dans le composante 'resabel'
      $source = Base_Donnees::$prefix_table . 'roles_membres';
      $bdd = Base_Donnees::accede();
      try {
        $requete= $bdd->prepare("SELECT COUNT(*) as n FROM " . $source . " WHERE code_membre = :code_membre AND code_role = 'admin' AND code_composante = 'resabel'");
        $requete->bindParam(':code_membre', $this->personne->code(), PDO::PARAM_INT);
        $requete->execute();
        if ($resultat = $requete->fetch(PDO::FETCH_OBJ)) {
         $est_admin = ($resultat->n == 1);
        }
       } catch (PDOexception $e) {
          Base_Donnees::sortir_sur_exception(self::source(), $e);
       }
       return $est_admin;
     }
    
    public function modifier_date_derniere_connexion() {
      $bdd = Base_Donnees::accede();
      try {
        $requete= $bdd->prepare("UPDATE " . self::source()
                              . " SET derniere_connexion = CURRENT_TIMESTAMP() WHERE code = :code_membre");
        $requete->bindParam(':code_membre', $this->personne->code(), PDO::PARAM_INT);
        $requete->execute();
      } catch (PDOexception $e) {
        die("Erreur Mise a jour " . self::source() . " date derniere connexion pour " . $this->critere_recherche() . " : ligne " . $e->getLine() . " :<br /> ". $e->getMessage());
      }
    }
    
    
  }
  // ==========================================================================
?>
