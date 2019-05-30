<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classe Enregistrement_Membre : interface base donnees
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : Classes Membre, Calendrier et Base_Donnees
  //               dictionnaire de donnees cree dans personnes.php :
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 08-dec-2018 pchevaillier@gmail.com
  // revision : 23-dec-2018 pchevaillier@gmail.com requetes preparees
  // revision : 23-dec-2018 pchevaillier@gmail.com requetes preparees
  // revision : 28-dec-2018 pchevaillier@gmail.com renomme Enregistrement_Membre
  // revision : 03-mar-2019 pchevaillier@gmail.com fonction collecter
  // revision : 16-mar-2019 pchevaillier@gmail.com collecter : criteres de selection
  // revision : 04-mai-2019 pchevaillier@gmail.com modifier_niveau_debutants
  // --------------------------------------------------------------------------
  // commentaires :
  // - en chantier : pas complet
  // - lire, ajouter, modifier, supprimer,
  //   tester_existe, compter, collecter, verfier_xxx
  // attention :
  // - 
  // a faire :
  // - dans lire : recuperer la commune
  // - ajouter lire_roles : ? personne qui depend de role ou l'inverse ?
  // ==========================================================================

  require_once 'php/metier/membre.php';

  require_once 'php/metier/calendrier.php';

  class Erreur_Membre_Introuvable extends Exception { }
  class Erreur_Mot_Passe_Membre extends Exception { }
  class Erreur_Doublon_Identifiant_Membre extends Exception { }
  
  // ==========================================================================
  class Enregistrement_Membre {
    static function source() {
      return Base_Donnees::$prefix_table . 'membres';
    }
    
    private $membre = null;
    public function membre() { return $this->membre; }
    public function def_membre($membre) { $this->membre = $membre; }
    
    public function verifier_identite($mot_passe) {
      $identification_ok = false;
      $bdd = Base_Donnees::accede();
      $requete = $bdd->prepare("SELECT code, actif, identifiant, connexion, mot_passe, prenom, nom, cdb FROM " . self::source() . " WHERE identifiant = :identifiant LIMIT 1");
      $requete->bindParam(':identifiant', $this->membre->identifiant, PDO::PARAM_STR);
      try {
        $requete->execute();
        if ($membre = $requete->fetch(PDO::FETCH_OBJ)) {//($resultat->rowCount() > 0) {
          if ($membre->mot_passe != $mot_passe) {
            throw new Erreur_Mot_Passe_Membre();
            return $identification_ok;
          } else {
            $this->membre->def_code($membre->code);
            $this->membre->prenom = utf8_encode($membre->prenom);
            $this->membre->nom = utf8_encode($membre->nom);
            $this->membre->def_autorise_connecter($membre->connexion);
            $this->membre->def_chef_de_bord($membre->cdb);
            $this->membre->def_actif($membre->actif);
            $identification_ok = true;
          }
        } else {
          throw new Erreur_Membre_Introuvable();
          return $identification_ok;
        }
      } catch (PDOException $e) {
       Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      //$resultat->closeCursor();
      return $identification_ok;
    }
    
    public static function generer_nouveau_code() {
      $annee = date("y");
      $code = $annee * 1000;
      $code_debut = $code;
      $code_fin = ($annee + 1) * 1000;
      try {
        $bdd = Base_Donnees::accede();
        $requete = $bdd->prepare("SELECT MAX(code) AS code FROM ". self::source() . " WHERE code BETWEEN :debut AND :fin");
        $requete->bindParam(':debut', $code_debut, PDO::PARAM_INT);
        $requete->bindParam(':fin', $code_fin, PDO::PARAM_INT);
        $requete->execute();
      if ($resultat = $requete->fetch(PDO::FETCH_OBJ))
        if ($resultat->code)
          $code = $resultat->code + 1;
      } catch (PDOException  $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $code;
    }
    
    public static function generer_mot_passe() {
      // TODO : rechercher le bon club
      $mot_passe = "";
      try {
        $bdd = Base_Donnees::accede();
        $requete = $bdd->prepare("SELECT mot_passe FROM rsbl_club WHERE code = 1 LIMIT 1");
        $requete->execute();
        if ($resultat = $requete->fetch(PDO::FETCH_OBJ))
          $mot_passe = $resultat->mot_passe;
      } catch (PDOException  $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $mot_passe;
    }
    
    
    public function verifier_identifiant_unique($identifiant) {
      $unique = false;
      try {
        $bdd = Base_Donnees::accede();
        $requete= $bdd->prepare("SELECT COUNT(*) AS n FROM " . self::source() . " WHERE identifiant = :identifiant and code != :code");
        $requete->bindParam(':identifiant', $identifiant, PDO::PARAM_STR);
        $code = $this->membre->code();
        $requete->bindParam(':code', $code, PDO::PARAM_INT);
        $requete->execute();
        if ($resultat = $requete->fetch(PDO::FETCH_OBJ))
          $unique = ($resultat->n == 0);
      } catch (PDOException  $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
     
      return $unique;
    }
    
    /*
     * recherche les informations dans la base de donnees
     */
    public function lire() {
      $trouve = false;
      try {
        $bdd = Base_Donnees::accede();
        $requete= $bdd->prepare("SELECT * FROM " . self::source() . " WHERE code = :code_membre LIMIT 1");
        $code = $this->membre->code();
        $requete->bindParam(':code_membre', $code, PDO::PARAM_INT);
        $requete->execute();
        if ($membre = $requete->fetch(PDO::FETCH_OBJ)) {
          $this->initialiser_depuis_table($membre);
          $trouve = true;
        } else {
          throw new Erreur_Membre_Introuvable();
          return $trouve;
        }
      } catch (PDOexception $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $trouve;
    }
    
    private function initialiser_depuis_table($donnee) {
      $cal = new Calendrier();
      
      $this->membre->identifiant = $donnee->identifiant;
      $this->membre->def_actif($donnee->actif);
      $this->membre->def_autorise_connecter($donnee->connexion);
      $this->membre->niveau = $donnee->niveau;
      $this->membre->genre = $donnee->genre; // deja en utf8 : pas besoin d'encoder
      $this->membre->prenom = $donnee->prenom; // deja en utf8 : pas besoin d'encoder
      $this->membre->nom = $donnee->nom;
      if ($donnee->date_naissance)
        $this->membre->date_naissance = $cal->def_depuis_date_sql($donnee->date_naissance);
      $this->membre->code_commune = $donnee->code_commune;
      $this->membre->rue = $donnee->rue;
      $this->membre->telephone = $donnee->telephone;
      $this->membre->telephone2 = $donnee->telephone2;
      $this->membre->courriel = $donnee->courriel;
      $this->membre->def_chef_de_bord($donnee->cdb);
      if ($donnee->derniere_connexion)
        $this->membre->date_derniere_connexion = $cal->def_depuis_timestamp_sql($donnee->derniere_connexion);
      $this->membre->num_licence = $donnee->num_licence;
    }
    
    public function recherche_si_admin() {
      $est_admin = false;
      // teste si la membre a le role admin dans le composante 'resabel'
      $source = Base_Donnees::$prefix_table . 'roles_membres';
      $bdd = Base_Donnees::accede();
      try {
        $requete= $bdd->prepare("SELECT COUNT(*) as n FROM " . $source . " WHERE code_membre = :code_membre AND code_role = 8 AND code_composante = 2");
        $code = $this->membre->code();
        $requete->bindParam(':code_membre', $code, PDO::PARAM_INT);
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
        $code = $this->membre->code();
        $requete->bindParam(':code_membre', $code, PDO::PARAM_INT);
        $requete->execute();
      } catch (PDOexception $e) {
        die("Erreur Mise a jour " . self::source() . " date derniere connexion pour " . $code . " : ligne " . $e->getLine() . " :<br /> ". $e->getMessage());
      }
    }
    
    public function modifier_niveau($valeur) {
      $bdd = Base_Donnees::accede();
      try {
        $requete= $bdd->prepare("UPDATE " . self::source()
                                . " SET niveau = :niv WHERE code = :code_membre");
        $code = $this->membre->code();
        $requete->bindParam(':code_membre', $code, PDO::PARAM_INT);
        $requete->bindParam(':niv', $valeur, PDO::PARAM_INT);
        $requete->execute();
        $this->membre->niveau = $valeur;
        $requete->closeCursor();
      } catch (PDOexception $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }

    }
    
    
    public function modifier_cdb($valeur) {
      $bdd = Base_Donnees::accede();
      try {
        $requete= $bdd->prepare("UPDATE " . self::source()
                                . " SET cdb = :cdb WHERE code = :code_membre");
        $code = $this->membre->code();
        $requete->bindParam(':code_membre', $code, PDO::PARAM_INT);
        $requete->bindParam(':cdb', $valeur, PDO::PARAM_INT);
        $requete->execute();
        $this->membre->def_chef_de_bord($valeur);
      } catch (PDOexception $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }

    }
    
    public static function modifier_niveaux($valeur_actuelle, $nouvelle_valeur) {
      $bdd = Base_Donnees::accede();
      try {
        $requete= $bdd->prepare("UPDATE " . self::source()
                                . " SET niveau = :nouveau WHERE niveau = :actuel AND actif = 1");
        $requete->bindParam(':actuel', $valeur_actuelle, PDO::PARAM_INT);
        $requete->bindParam(':nouveau', $nouvelle_valeur, PDO::PARAM_INT);
        $requete->execute();
      } catch (PDOexception $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
       $requete->closeCursor();
    }
    
    
    public function ajouter() {
      $status = true;
      try {
        $bdd = Base_Donnees::accede();
        $code_sql = "INSERT INTO " . self::source()
        . " (code, identifiant, mot_passe"
        . ", actif, connexion, niveau, cdb"
        . ", genre, prenom, nom, date_naissance"
        . ", code_commune, rue"
        . ", telephone, telephone2, courriel"
        . ", num_licence"
        . ") VALUES"
        . " (:code, :identifiant, :mot_passe"
        . ", :actif, :connexion, :niveau, :cdb"
        . ", :genre, :prenom, :nom, :date_naissance"
        . ", :code_commune, :rue"
        . ", :telephone, :telephone2, :courriel"
        . ", :num_licence"
        . " )";
        
        $requete= $bdd->prepare($code_sql);
        
        $code = $this->membre->code();
        $requete->bindParam(':code', $code, PDO::PARAM_INT);
        $requete->bindParam(':identifiant', $this->membre->identifiant, PDO::PARAM_STR);
        $requete->bindParam(':mot_passe', $this->membre->mot_passe, PDO::PARAM_STR);
        
        $actif = ($this->membre->est_actif()) ? 1: 0;
        $requete->bindParam(':actif', $actif, PDO::PARAM_INT);
        $connexion = ($this->membre->est_autorise_connecter()) ? 1: 0;
        $requete->bindParam(':connexion', $connexion, PDO::PARAM_INT, 1);
        $cdb = ($this->membre->est_chef_de_bord()) ? 1: 0;
        $requete->bindParam(':cdb', $cdb, PDO::PARAM_INT);
        $requete->bindParam(':niveau', $this->membre->niveau, PDO::PARAM_INT);
        
        $requete->bindParam(':genre', $this->membre->genre, PDO::PARAM_STR);
        $requete->bindParam(':prenom', $this->membre->prenom, PDO::PARAM_STR);
        $requete->bindParam(':nom', $this->membre->nom, PDO::PARAM_STR);
        
        if ($this->membre->date_naissance) {
          $cal = new Calendrier();
          $date_naissance = $cal->formatter_date_sql($this->membre->date_naissance);
          $requete->bindParam(':date_naissance', $date_naissance, PDO::PARAM_STR);
        } else {
          $requete->bindParam(':date_naissance', $this->membre->date_naissance, PDO::PARAM_NULL);
        }
        
        $requete->bindParam(':code_commune', $this->membre->code_commune, PDO::PARAM_INT);
        $requete->bindParam(':rue', $this->membre->rue, PDO::PARAM_STR);
        $requete->bindParam(':telephone', $this->membre->telephone, PDO::PARAM_STR);
        $requete->bindParam(':telephone2', $this->membre->telephone2, PDO::PARAM_STR);
        $requete->bindParam(':courriel', $this->membre->courriel, PDO::PARAM_STR);
        
        $requete->bindParam(':num_licence', $this->membre->num_licence);
        
        $requete->execute();
        
      } catch (PDOexception $e) {
        die("Erreur Mise a jour " . self::source() . " informations pour " . $code . " : ligne " . $e->getLine() . " :<br /> ". $e->getMessage());
        //Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      $requete->closeCursor();
      return $status;
    }
    
    
    public function modifier() {
      $bdd = Base_Donnees::accede();
      try {
        $requete= $bdd->prepare("UPDATE " . self::source() . " SET "
                                . "identifiant = :identifiant"
                                . ", actif = :actif, connexion = :connexion"
                                . ", niveau = :niveau"
                                . ", genre = :genre"
                                . ", prenom = :prenom, nom = :nom"
                                . ", date_naissance = :date_naissance"
                                . ", code_commune = :code_commune, rue = :rue"
                                . ", telephone = :telephone, telephone2 = :telephone2"
                                . ", courriel = :courriel"
                                . ", cdb = :cdb"
                                . ", num_licence = :num_licence"
                                . " WHERE code = :code_membre");
        $requete->bindParam(':identifiant', $this->membre->identifiant, PDO::PARAM_STR);
        $actif = ($this->membre->est_actif()) ? 1: 0;
        $requete->bindParam(':actif', $actif, PDO::PARAM_INT);
        $connexion = ($this->membre->est_autorise_connecter()) ? 1: 0;
        $requete->bindParam(':connexion', $connexion, PDO::PARAM_INT, 1);
        
        $requete->bindParam(':niveau', $this->membre->niveau, PDO::PARAM_INT);
        $requete->bindParam(':genre', $this->membre->genre, PDO::PARAM_STR);
        $requete->bindParam(':prenom', $this->membre->prenom, PDO::PARAM_STR);
        $requete->bindParam(':nom', $this->membre->nom, PDO::PARAM_STR);
        if ($this->membre->date_naissance) {
          $cal = new Calendrier();
          $date_naissance = $cal->formatter_date_sql($this->membre->date_naissance);
          $requete->bindParam(':date_naissance', $date_naissance, PDO::PARAM_STR);
        } else {
          $requete->bindParam(':date_naissance', $this->membre->date_naissance, PDO::PARAM_NULL);
        }
        $requete->bindParam(':code_commune', $this->membre->code_commune, PDO::PARAM_INT);
        $requete->bindParam(':rue', $this->membre->rue, PDO::PARAM_STR);
        $requete->bindParam(':telephone', $this->membre->telephone, PDO::PARAM_STR);
        $requete->bindParam(':telephone2', $this->membre->telephone2, PDO::PARAM_STR);
        $requete->bindParam(':courriel', $this->membre->courriel, PDO::PARAM_STR);
        
        $cdb = ($this->membre->est_chef_de_bord()) ? 1: 0;
        $requete->bindParam(':cdb', $cdb, PDO::PARAM_INT);
        $requete->bindParam(':num_licence', $this->membre->num_licence);
        
        $code = $this->membre->code();
        $requete->bindParam(':code_membre', $code, PDO::PARAM_INT);
        $requete->execute();
      } catch (PDOexception $e) {
        die("Erreur Mise a jour " . self::source() . " informations pour " . $code . " : ligne " . $e->getLine() . " :<br /> ". $e->getMessage());
      }
    }
    
    static function collecter($criteres_selection, $composante, $role, & $personnes) {
      $status = false;
      $cal = new Calendrier();
      $personnes = array();
      
      // definition de la source des donnees
      $table_membres = self::source();
      $table_communes = Base_Donnees::$prefix_table . 'communes';
      $table_roles = Base_Donnees::$prefix_table . 'roles_membres';
      
      $source = $table_membres;
      if ((strlen($composante) > 0) || (strlen($role) > 0))
        $source = $source .  ', ' .  $table_roles;
      //echo '<p>source ' . $source . '</p>';
      
      $critere = "";
      $i_crit = 0;
      $n_crit = count($criteres_selection);
      $cdb_oui = false;
      $cdb_non = false;
      
      if ($n_crit > 0) {
        $operateur = "";
        $critere = $critere . " WHERE ";
        foreach ($criteres_selection as $cle => $valeur) {
          $operateur = ($i_crit > 0 && $i_crit < $n_crit) ? " AND " : "";
          $i_crit = $i_crit + 1;
          if ($cle == 'act')
            $critere = $critere . $operateur . " actif = '" . $valeur . "' ";
          elseif ($cle == 'cnx')
            $critere = $critere . $operateur . " connexion = '" . $valeur . "' ";
          elseif ($cle == 'prn')
            $critere = $critere . $operateur . " prenom LIKE '" . $valeur . "%' ";
          elseif ($cle == 'nom')
            $critere = $critere . $operateur . $table_membres . ".nom LIKE '" . $valeur . "%' ";
          elseif ($cle == 'cmn' && $valeur > 0)
            $critere = $critere . $operateur . " code_commune = '" . $valeur . "' ";
          elseif ($cle == 'cdb' && $valeur > 0)
            $critere = $critere . $operateur . " cdb = '" . (($valeur == 2) ? 0 : 1) . "' ";
          elseif ($cle == 'niv' && $valeur > 0)
              $critere = $critere . $operateur . " niveau " . (($valeur >= 2) ? ">=" : "<") . "2 ";
          else
            echo $cle . ' ' . $valeur . '<br />';
        }
      }
      
      $tri =  " ORDER BY " . $table_membres . ".prenom, " . $table_membres . ".nom ";
      try {
        $bdd = Base_Donnees::accede();
        $requete = "SELECT " . $table_membres . ".code AS code, identifiant, genre, prenom, " . $table_membres . ".nom AS nom, telephone, telephone2, rue, courriel, actif, connexion, niveau, date_naissance, cdb, derniere_connexion, num_licence, " . $table_communes . ".nom AS nom_commune" . " FROM " . $source . " INNER JOIN " . $table_communes . " ON " . $table_communes . ".code = " . $table_membres . ".code_commune " . $critere . $tri;
        //echo '<p>' . $requete . '</p>';
        $resultat = $bdd->query($requete);
        
        while ($donnee = $resultat->fetch(PDO::FETCH_OBJ)) {
          $personne = new Membre($donnee->code);
          
          // Proprietes d'une personne
          $personne->genre = $donnee->genre;
          $personne->prenom = $donnee->prenom;
          $personne->nom = $donnee->nom;
          $personne->telephone = $donnee->telephone;
          $personne->telephone2 = $donnee->telephone2;
          $personne->courriel = $donnee->courriel;
          $personne->nom_commune = $donnee->nom_commune;
          
          $personne->rue = $donnee->rue;
          $personne->telephone2 = $donnee->telephone2;
          
          // proprietes d'un membre
          
          $personne->identifiant = $donnee->identifiant;
          $personne->def_actif($donnee->actif);
          $personne->def_autorise_connecter($donnee->connexion);
          $personne->niveau = $donnee->niveau;
          if ($donnee->date_naissance)
            $personne->date_naissance = $cal->def_depuis_date_sql($donnee->date_naissance);
           $personne->def_chef_de_bord($donnee->cdb);
          if ($donnee->derniere_connexion)
            $personne->date_derniere_connexion = $cal->def_depuis_timestamp_sql($donnee->derniere_connexion);
          $personne->num_licence = $donnee->num_licence;
          
          $personnes[$personne->code()] = $personne;
        }
      
      } catch (PDOException $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $status;
    }
  }
  // ==========================================================================
?>
