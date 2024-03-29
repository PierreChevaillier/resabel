<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classe Enregistrement_Support_Activite
  //               operations sur la base de donnees
  // copyright (c) 2018-2020 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : cf. require_once + classe Base_Donnees + structure table
  //               code_type pour instantiation objet
  //               de la bonne sous-classe de Support_Activite
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 10-jun-2019 pchevaillier@gmail.com
  // revision : 11-jan-2020 pchevaillier@gmail.com champs loisir, competition
  // revision : 23-jan-2020 pchevaillier@gmail.com champs nb_pers (type support)
  // --------------------------------------------------------------------------
  // commentaires :
  // - En evolution
  // attention :
  // - non teste
  // a faire :
  // -
  // ==========================================================================
  
  require_once 'php/metier/support_activite.php';
  
  class Erreur_Support_Activite_Introuvable extends Exception { }
  
  // ==========================================================================
  class Enregistrement_Support_Activite {
    private $support_activite = null;
    public function support_activite() { return $this->support_activite; }
    public function def_support_activite($support_activite) {
      $this->support_activite = $support_activite;
    }
    
    static function source() {
      return Base_Donnees::$prefix_table . 'supports';
    }
    
    public function lire_identite() {
      $trouve = false;
      try {
        $bdd = Base_Donnees::acces();
        
        $code_sql = "SELECT support.code, numero, support.nom AS nom, type.nom_court AS nom_type, type.code_type AS code_type FROM rsbl_supports AS support INNER JOIN rsbl_types_support AS type ON support.code_type_support = type.code WHERE support.code = :code_support_activite LIMIT 1";
        
        $requete= $bdd->prepare($code_sql);
        $code = $this->support_activite->code();
        $requete->bindParam(':code_support_activite', $code, PDO::PARAM_INT);
        
        $requete->execute();
        
        if ($donnee = $requete->fetch(PDO::FETCH_OBJ)) {
          // Il faut trouver le type de l'objet a instancier (pas terrible...)
          if ($donnee->code_type == 1) {
            $this->support_activite = new Bateau($code);
            $this->support_activite->def_numero($donnee->numero);
          } elseif ($donnee->code_type == 2) {
            $this->support_activite = new Plateau_Ergo($code);
          }
          $this->support_activite->def_nom($donnee->nom);
          
          $this->support_activite->type = new Type_Support_Activite($donnee->code_type);
          $this->support_activite->type->def_nom($donnee->nom_type);
          $trouve = true;
        } else {
          throw new Erreur_Support_Activite_Introuvable();
          return $trouve;
        }
      } catch (PDOexception $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $trouve;
    }
    
    public function lire(int $code) {
      $trouve = false;
      try {
        $bdd = Base_Donnees::acces();

        //$code_sql = "SELECT support.code, numero, support.nom AS nom, type.nom_court AS nom_type, type.code_type AS code_type FROM rsbl_supports AS support INNER JOIN rsbl_types_support AS type ON support.code_type_support = type.code WHERE support.code = :code_support_activite LIMIT 1";
        $code_sql = "SELECT support.code AS code, numero, modele, constructeur, annee_construction, actif, competition, loisir, support.nom AS nom, support.nombre_postes AS nb_postes, nb_initiation_min, nb_initiation_max, type_support.nom_court AS nom_type, type_support.code AS type, type_support.code_type AS code_type, type_support.nb_pers_min AS pers_min, type_support.nb_pers_max AS pers_max, type_support.cdb_requis AS cdb_requis FROM rsbl_supports AS support INNER JOIN rsbl_types_support AS type_support ON (support.code_type_support = type_support.code) WHERE support.code = :code_support_activite LIMIT 1";
        
        $requete= $bdd->prepare($code_sql);
        //$code = $this->support_activite->code();
        $requete->bindParam(':code_support_activite', $code, PDO::PARAM_INT);

        $requete->execute();

        if ($donnee = $requete->fetch(PDO::FETCH_OBJ)) {
          // Il faut trouver le type de l'objet a instancier (pas terrible...)
          if ($donnee->code_type == 1) {
            $this->support_activite = new Bateau($code);
          } elseif ($donnee->code_type == 2) {
            $this->support_activite = new Plateau_Ergo($code);
            $this->support_activite->nombre_postes = $donnee->nb_postes;
          }
          $this->support_activite->def_numero($donnee->numero);
          $this->support_activite->def_nom($donnee->nom);
          $this->support_activite->actif = ($donnee->actif == '1');
          $this->support_activite->pour_competition = ($donnee->competition == '1');
          $this->support_activite->pour_loisir = ($donnee->loisir == '1');
          $this->support_activite->nombre_initiation_min = $donnee->nb_initiation_min;
          $this->support_activite->nombre_initiation_max = $donnee->nb_initiation_max;

          $this->support_activite->type = new Type_Support_Activite($donnee->code_type);
          $this->support_activite->type->def_nom($donnee->nom_type);
          $this->support_activite->type->nombre_personnes_min = $donnee->pers_min;
          $this->support_activite->type->nombre_personnes_max = $donnee->pers_max;
          $this->support_activite->type->chef_de_bord_requis = ($donnee->cdb_requis == '1');

          // Champs non critiques
          $this->support_activite->modele = $donnee->modele;
          $this->support_activite->constructeur = $donnee->constructeur;
          $this->support_activite->annee_construction = $donnee->annee_construction;

          $trouve = true;
          
          // TODO : le site d'activite
        } else {
          throw new Erreur_Support_Activite_Introuvable();
          return $trouve;
        }
      } catch (PDOexception $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $trouve;
    }
    
    public function modifier() {
      $status = false;
      if (is_null($this->support_activite)) {
        echo "Enregistrement-Support_Activite::modifier : support $this->support_activite est nul";
        return $status;
      }
      try {
        $bdd = Base_Donnees::acces();
        $support = $this->support_activite();
        $requete= $bdd->prepare("UPDATE " . self::source()
                                          . " SET numero = :numero"
                                          . ", nom = :nom"
                                          . ", modele = :modele"
                                          . ", constructeur = :constructeur"
                                          . ", annee_construction = :annee_constr"
                                          . ", actif = :actif"
                                          . ", competition = :compet"
                                          . ", loisir = :loisir"
                                          . ", nb_initiation_min = :init_min"
                                          . ", nb_initiation_max = :init_max"
                                          . " WHERE code = :code");
        $requete->bindParam(':code', $support->code(), PDO::PARAM_INT);
        $numero = $support->numero();
        $requete->bindParam(':numero', $numero, PDO::PARAM_STR);
        $nom = $support->nom();
        $requete->bindParam(':nom', $nom, PDO::PARAM_STR);
        $requete->bindParam(':modele', $support->modele, PDO::PARAM_STR);
        $requete->bindParam(':constructeur', $support->constructeur, PDO::PARAM_STR);
        $requete->bindParam(':annee_constr', $support->annee_construction, PDO::PARAM_INT);
        $actif = $support->est_actif() ? 1: 0;
        $requete->bindParam(':actif', $actif, PDO::PARAM_INT);
        $compet = $support->est_pour_competition() ? 1: 0;
        $requete->bindParam(':compet', $compet, PDO::PARAM_INT);
        $loisir = $support->est_pour_loisir() ? 1: 0;
        $requete->bindParam(':loisir', $loisir, PDO::PARAM_INT);
        $requete->bindParam(':init_min', $support->nombre_initiation_min, PDO::PARAM_INT);
        $requete->bindParam(':init_max', $support->nombre_initiation_max, PDO::PARAM_INT);

        $requete->execute();
        $status = true;
      } catch (PDOexception $e) {
       die("Erreur Mise a jour " . self::source() . " informations pour " . $code . " : ligne " . $e->getLine() . " :<br /> ". $e->getMessage());
       //Base_Donnees::sortir_sur_exception(self::source(), $e);
      }

      $requete->closeCursor();
      return $status;
    }
    
    public function ajouter() {
      $status = false;
      $nouveau_code = 0;
      try {
        $bdd = Base_Donnees::acces();
        $bdd->beginTransaction();
        $code_sql = "";
        if (is_a($this->support_activite, 'Bateau'))
          $code_sql = "INSERT INTO " . self::source()
                      . " (numero, nom, code_type_support" // champs obligatoires
//                      . ", modele, constructeur, annee_construction, fichier_image"
                      . ", actif, code_site_base" // champs obligatoires
                      . ", competition, loisir" // champs obligatoires
 //                     . ", nb_initiation_min, nb_initiation_max"
                      . ") VALUES"
                      . " (:numero, :nom, :code_type_support"
 //                     . ", :modele, :constructeur, :annee_construction, :fichier_image"
                      . ", :actif, :code_site_base"
                      . ", :competition, :loisir"
 //                     . ", :nb_initiation_min, nb_initiation_max"
                      . " )";
        else
          $code_sql = "INSERT INTO " . self::source()
                      . " (numero, nom, code_type_support" // champs obligatoires
                      . ", modele, constructeur, annee_construction, fichier_image"
                      . ", actif, code_site_base" // champs obligatoires
                      . ", nombre_postes"
                      . ", competition, loisir" // champs obligatoires
                      . ", nb_initiation_min, nb_initiation_max"
                      . ") VALUES"
                      . " (:numero, :nom, :code_type_support"
                      . ", :modele, :constructeur, :annee_construction, :fichier_image"
                      . ", :actif, :code_site_base"
                      . ", :nombre_postes"
                      . ", :competition, :loisir"
                      . ", :nb_initiation_min, nb_initiation_max"
                      . " )";

        $requete= $bdd->prepare($code_sql);
        // champs communs aux differentes sous-classes de Support_Actvite
        $requete->bindParam(':numero', $this->support_activite->numero(), PDO::PARAM_STR);
        $requete->bindParam(':nom', $this->support_activite->nom(), PDO::PARAM_STR);
        $code_type_support = $this->support_activite->type->code();
        $requete->bindParam(':code_type_support', $code_type_support, PDO::PARAM_INT);
        $actif = $this->support_activite->est_actif() ? 1 : 0;
        $requete->bindParam(':actif', $actif, PDO::PARAM_INT);
        $pour_competition = $this->support_activite->est_pour_competition() ? 1 : 0;
        $requete->bindParam(':competition', $pour_competition, PDO::PARAM_INT);
        $pour_loisir = $this->support_activite->est_pour_loisir() ? 1 : 0;
        $requete->bindParam(':loisir', $pour_loisir, PDO::PARAM_INT);
        $requete->bindParam(':nb_initiation_min', $this->support_activite->npmbre_initiation_min, PDO::PARAM_INT);
        $requete->bindParam(':nb_initiation_max', $this->support_activite->npmbre_initiation_max, PDO::PARAM_INT);

        $code_site_base = 0;
        
        if (is_a($this->support_activite, 'Bateau')) {
          // Champs (ou valeurs) specifiques aux bateaux
          $code_site_base = 1;
        } else {
          $code_site_base = 2;
          // Champs specifiques aux Plateau_Ergo (pour l'instant que 2 sous-classes)
        }
        $requete->bindParam(':code_site_base', $code_site_base, PDO::PARAM_INT);
        
        $requete->execute();
        // Recuperation du code du support qui vient d'etre cree
        // champ AUTO_INCREMENT qui est la cle primaire de la table
        $nouveau_code = $bdd->lastInsertId(); // doit etre dans la transaction (avant le commit)
        //echo "LastInsertedId: " .  $nouveau_code;
        $this->support_activite->def_code($nouveau_code);
        /*
        $requete_code = $bdd->prepare("SELECT MAX(code) AS code FROM ". self::source());
        $resultat = $requete_code->fetch(PDO::FETCH_OBJ))
        $this->support_activite->def_code($resultat->code); // on va eventuellement a la faute car dans bloc try - catch
         */
        $bdd->commit();
        $status = true;
      } catch (PDOexception $e) {
        $bdd->rollBack();
        die("Erreur ajout enregistrement dans " . self::source() . " pour code " . $code . " : ligne " . $e->getLine() . " :<br /> ". $e->getMessage());
        //Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      
      $requete->closeCursor();
      return $status;
    }

    static function collecter($critere_selection, $critere_tri, & $support_activites) {
      $status = false;
      $support_activites = array();
      $selection = (strlen($critere_selection) > 0) ? " WHERE " . $critere_selection . " " : "";
      $tri = (strlen($critere_tri) > 0) ? " ORDER BY " . $critere_tri . " " : "";
      try {
        $bdd = Base_Donnees::acces();
        //$requete = "SELECT support.code AS code, numero, competition, loisir, support.nom AS nom, support.nombre_postes AS nb_postes, type.nom_court AS nom_type, type.code AS type, type.code_type AS code_type, type.nb_pers_min AS pers_min, type.nb_pers_max AS pers_max, type.cdb_requis AS cdb_requis FROM rsbl_supports AS support INNER JOIN rsbl_types_support AS type ON support.code_type_support = type.code " . $selection . $tri;
        
        $requete = "SELECT support.code AS code, numero, actif, competition, loisir, support.nom AS nom, support.nombre_postes AS nb_postes, type_support.nom_court AS nom_type, type_support.code AS type, type_support.code_type AS code_type, type_support.nb_pers_min AS pers_min, type_support.nb_pers_max AS pers_max, type_support.cdb_requis AS cdb_requis FROM rsbl_supports AS support INNER JOIN rsbl_types_support AS type_support ON (support.code_type_support = type_support.code)" . $selection . $tri;
        
        //echo "<p>" . $requete . "</p>";
        $resultat = $bdd->query($requete);
        while ($donnee = $resultat->fetch(PDO::FETCH_OBJ)) {
          if ($donnee->code_type == 1) {
            $support_activite = new Bateau($donnee->code);
          } elseif ($donnee->code_type == 2) {
            $support_activite = new Plateau_Ergo($donnee->code);
            $support_activite->nombre_postes = $donnee->nb_postes;
          }
          $support_activite->def_numero($donnee->numero);
          $support_activite->actif = ($donnee->actif == '1');
          $support_activite->pour_competition = $donnee->competition;
          $support_activite->pour_loisir = $donnee->loisir;
          $support_activite->def_nom($donnee->nom);
          $support_activite->type = new Type_Support_Activite($donnee->type);
          $support_activite->type->def_nom($donnee->nom_type);
          $support_activite->type->nombre_personnes_min = $donnee->pers_min;
          $support_activite->type->nombre_personnes_max = $donnee->pers_max;
          $support_activite->type->chef_de_bord_requis = ($donnee->cdb_requis == '1');
          $support_activites[$support_activite->code()] = $support_activite;
        }
      } catch (PDOException $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $status;
      }
      
    
    public function modifier_actif(int $code, int $valeur) {
      $bdd = Base_Donnees::acces();
      try {
        $requete= $bdd->prepare("UPDATE " . self::source()
                                . " SET actif = :etat WHERE code = :code");
        $requete->bindParam(':code', $code, PDO::PARAM_INT);
        $requete->bindParam(':etat', $valeur, PDO::PARAM_INT);
        $requete->execute();
        if (!is_null($this->support_activite))
          $this->support_activite->actif = ($valeur == 1);
      } catch (PDOexception $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }

    }

  }
  
  // ==========================================================================
?>
