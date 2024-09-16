<?php
/* ============================================================================
 * Resabel - systeme de REServAtion de Bateau En Ligne
 * Copyright (C) 2024 Pierre Chevaillier
 * contact: pchevaillier@gmail.com 70 allee de Broceliande, 29200 Brest, France
 * ----------------------------------------------------------------------------
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License,
 * or any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 * ----------------------------------------------------------------------------
 * description : Enregistrement_Membre : interface base donnees
 * utilisation : php - require_once <chemin_vers_ce_fichier_php>
 * dependances :
 * - aucune
 * ----------------------------------------------------------------------------
 * creation : 08-dec-2018 pchevaillier@gmail.com
 * revision : 20-nov-2023 pchevailler@gmail.com tables mmebres ET connexion
 * revision : 16-sep-2024 pchevailler@gmail.com + formatter_telephone_table
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * -
 * a faire :
 * - voir TODOs
 * - lire_identite : nom, prenom
 * - lire_info_activite
 * - dans lire : recuperer la commune et type de licence
 * - ajouter lire_roles : ? personne qui depend de role ou l'inverse ?
 * ============================================================================
 */

require_once 'php/metier/membre.php';
require_once 'php/metier/calendrier.php';

require_once 'php/bdd/enregistrement_connexion.php';
  
class Erreur_Membre_Introuvable extends Exception { }
class Erreur_Mot_Passe_Membre extends Exception { }
class Erreur_Doublon_Identifiant_Membre extends Exception { }
  
// ============================================================================
  class Enregistrement_Membre {
    static function source() {
      return Base_Donnees::$prefix_table . 'membres';
    }
    
    private ? membre $membre = null;
    public function membre(): ? Membre { return $this->membre; }
    public function def_membre(Membre $membre): void { $this->membre = $membre; }
    
    static function formatter_telephone_table(string $numero): string {
      $mauvais_separateurs = array(' ', '.', '-', '/');
      $bon_separateur = '';
      $resultat = str_replace($mauvais_separateurs, $bon_separateur, $numero);
      return $resultat;
    }
    
    /*
    public function verifier_identite($mot_passe) {
      $identification_ok = false;
      $bdd = Base_Donnees::acces();
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
            $this->membre->prenom = $membre->prenom; // stocke en utf-8 dans la base
            $this->membre->nom = $membre->nom; // stocke en utf-8 dans la base
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
 */
    public static function generer_nouveau_code(): int {
      $annee = date("y");
      $code = $annee * 1000;
      $code_debut = $code;
      $code_fin = ($annee + 1) * 1000;
      try {
        $bdd = Base_Donnees::acces();
        $requete = $bdd->prepare("SELECT MAX(code) AS code FROM ". self::source() . " WHERE code BETWEEN :debut AND :fin");
        $requete->bindParam(':debut', $code_debut, PDO::PARAM_INT);
        $requete->bindParam(':fin', $code_fin, PDO::PARAM_INT);
        $requete->execute();
        if ($resultat = $requete->fetch(PDO::FETCH_OBJ))
          if ($resultat->code)
            $code = $resultat->code + 1;
        $requete->closeCursor();
      } catch (PDOException  $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $code;
    }
    
    public static function generer_mot_passe(): string {
      // TODO: rechercher le bon club
      // TODO: a deplacer dans la classe Enregistrement_Connexion
      $mot_passe = "";
      try {
        $bdd = Base_Donnees::acces();
        $requete = $bdd->prepare("SELECT mot_passe FROM rsbl_club WHERE code = 1 LIMIT 1");
        $requete->execute();
        if ($resultat = $requete->fetch(PDO::FETCH_OBJ))
          $mot_passe = $resultat->mot_passe;
        $requete->closeCursor();
      } catch (PDOException  $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $mot_passe;
    }
    
    
    /**
     * TODO: deplace dans Enregistrement_Connexion
     */
    public function verifier_identifiant_unique(string $identifiant): bool {
      $unique = false;
      try {
        $bdd = Base_Donnees::acces();
        $requete= $bdd->prepare("SELECT COUNT(*) AS n FROM " . self::source() . " WHERE identifiant = :identifiant and code != :code");
        $requete->bindParam(':identifiant', $identifiant, PDO::PARAM_STR);
        $code = $this->membre->code();
        $requete->bindParam(':code', $code, PDO::PARAM_INT);
        $requete->execute();
        if ($resultat = $requete->fetch(PDO::FETCH_OBJ))
          $unique = ($resultat->n == 0);
        $requete->closeCursor();
      } catch (PDOException  $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $unique;
    }
    
    /*
     * recherche les informations dans la base de donnees
     */
    public function lire(): bool {
      $trouve = false;
      try {
        $bdd = Base_Donnees::acces();
        $membres = self::source();
        $connexions = Enregistrement_Connexion::source();
        $code_sql = "SELECT genre, prenom, nom, date_naissance, code_commune, rue, telephone, telephone2, courriel, niveau, num_licence, cdb, CNX.identifiant AS identifiant, CNX.connexion AS connexion, CNX.actif AS actif FROM "
          . $membres . " AS MBR INNER JOIN " . $connexions  . " AS CNX ON MBR.code = CNX.code_membre "
        . " WHERE MBR.code = :code_membre LIMIT 1";
        $requete= $bdd->prepare($code_sql);
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
      //$cal = new Calendrier();
      
      //$cnx = $this->membre->connexion();
      
      // Donnees de connexion
      $this->membre->def_identifiant($donnee->identifiant);
      $this->membre->def_actif($donnee->actif);
      $this->membre->def_autorise_connecter($donnee->connexion);
      
      // Donnees sur la personne
      $this->membre->genre = $donnee->genre; // deja en utf8 : pas besoin d'encoder
      $this->membre->prenom = $donnee->prenom; // deja en utf8 : pas besoin d'encoder
      $this->membre->nom = $donnee->nom;
      if ($donnee->date_naissance)
        $this->membre->date_naissance = new Instant($donnee->date_naissance);
      $this->membre->code_commune = $donnee->code_commune;
      $this->membre->rue = $donnee->rue;
      $this->membre->telephone = $donnee->telephone;
      $this->membre->telephone2 = $donnee->telephone2;
      $this->membre->courriel = $donnee->courriel;
      
      // Donnees sur le membre liees a l'activite dans le club
      $this->membre->num_licence = $donnee->num_licence;
      $this->membre->def_niveau($donnee->niveau);
      $this->membre->def_chef_de_bord($donnee->cdb);
    }
    
    public function recherche_si_admin(): bool {
      $est_admin = false;
      // teste si la membre a le role admin dans le composante 'resabel'
      $source = Base_Donnees::$prefix_table . 'roles_membres';
      $bdd = Base_Donnees::acces();
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
    
    public function modifier_niveau(int $valeur): void {
      $bdd = Base_Donnees::acces();
      try {
        $requete= $bdd->prepare("UPDATE " . self::source()
                                . " SET niveau = :niv WHERE code = :code_membre");
        $code = $this->membre->code();
        $requete->bindParam(':code_membre', $code, PDO::PARAM_INT);
        $requete->bindParam(':niv', $valeur, PDO::PARAM_INT);
        $requete->execute();
        $this->membre->def_niveau($valeur);
      } catch (PDOexception $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
    }
    
    public function modifier_cdb(int $valeur): void {
      $bdd = Base_Donnees::acces();
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
    
    public static function modifier_niveaux(int $valeur_actuelle, int $nouvelle_valeur): void {
      $bdd = Base_Donnees::acces();
      try {
        $requete= $bdd->prepare("UPDATE " . self::source()
                                . " SET niveau = :nouveau WHERE niveau = :actuel AND actif = 1");
        $requete->bindParam(':actuel', $valeur_actuelle, PDO::PARAM_INT);
        $requete->bindParam(':nouveau', $nouvelle_valeur, PDO::PARAM_INT);
        $requete->execute();
      } catch (PDOexception $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
    }
    
    public function ajouter(): bool {
      $status = true;
      if (is_null($this->membre))
        return false;
      $membres = self::source();
      $connexions = Enregistrement_Connexion::source();

      $code_sql_mbr = "INSERT INTO " . $membres
      . " (code, niveau, cdb"
      . ", genre, prenom, nom, date_naissance"
      . ", code_commune, rue"
      . ", telephone, telephone2, courriel"
      . ", num_licence"
      . ") VALUES"
      . " (:code, :niveau, :cdb"
      . ", :genre, :prenom, :nom, :date_naissance"
      . ", :code_commune, :rue"
      . ", :telephone, :telephone2, :courriel"
      . ", :num_licence"
      . " )";

      // Attention : le mot de passe est gere a part (pas ici)
      $code_sql_cnx = "INSERT INTO " . $connexions
      . " (code_membre, identifiant, actif, connexion, date_creation)"
      . " VALUES"
      . " (:code, :identifiant, :actif, :connexion, :date_creation)";

      $code = $this->membre->code();

      $bdd = Base_Donnees::acces();
      $bdd->beginTransaction(); // car insertion dans 2 tables
      
      try {
        
        // --- Ajout dans la table membres
        $requete_mbr= $bdd->prepare($code_sql_mbr);
        
        $requete_mbr->bindParam(':code', $code, PDO::PARAM_INT);
        $cdb = ($this->membre->est_chef_de_bord()) ? 1: 0;
        $requete_mbr->bindParam(':cdb', $cdb, PDO::PARAM_INT);
        $requete_mbr->bindParam(':niveau', $this->membre->niveau, PDO::PARAM_INT);
        
        $requete_mbr->bindParam(':genre', $this->membre->genre, PDO::PARAM_STR);
        $requete_mbr->bindParam(':prenom', $this->membre->prenom, PDO::PARAM_STR);
        $requete_mbr->bindParam(':nom', $this->membre->nom, PDO::PARAM_STR);
        
        if (!is_null($this->membre->date_naissance)) {
          $date_naissance = $this->membre->date_naissance->date_sql(); //$cal->formatter_date_sql($this->membre->date_naissance);
          $requete_mbr->bindParam(':date_naissance', $date_naissance, PDO::PARAM_STR);
        } else {
          $requete_mbr->bindParam(':date_naissance', $this->membre->date_naissance, PDO::PARAM_NULL);
        }
        
        $requete_mbr->bindParam(':code_commune', $this->membre->code_commune, PDO::PARAM_INT);
        $requete_mbr->bindParam(':rue', $this->membre->rue, PDO::PARAM_STR);
        
        $tel1 = self::formatter_telephone_table($this->membre->telephone);
        $requete_mbr->bindParam(':telephone', $tel1, PDO::PARAM_STR);
        $tel2 = self::formatter_telephone_table($this->membre->telephone2);
        $requete_mbr->bindParam(':telephone2', $tel2, PDO::PARAM_STR);
        
        $requete_mbr->bindParam(':courriel', $this->membre->courriel, PDO::PARAM_STR);
        $requete_mbr->bindParam(':num_licence', $this->membre->num_licence, PDO::PARAM_STR);
        
        $requete_mbr->execute();
        
        // --- Ajout dans la table connexions
        $requete_cnx = $bdd->prepare($code_sql_cnx);
        
        $requete_cnx->bindParam(':code', $code, PDO::PARAM_INT);
        $identifiant = $this->membre->identifiant();
        $requete_cnx->bindParam(':identifiant', $identifiant, PDO::PARAM_STR);
        
        $actif = $this->membre->est_actif() ? 1: 0;
        $requete_cnx->bindParam(':actif', $actif, PDO::PARAM_INT, 1);
        $connexion = $this->membre->est_autorise_connecter() ? 1: 0;
        $requete_cnx->bindParam(':connexion', $connexion, PDO::PARAM_INT, 1);

        $date_creation = (Calendrier::maintenant())->date_heure_sql();
        $requete_cnx->bindParam(':date_creation', $date_creation, PDO::PARAM_STR);
        
        $requete_cnx->execute();
        
      } catch (PDOexception $e) {
        die("Erreur Insertion membre pour " . $code . " : ligne " . $e->getLine() . " : ". PHP_EOL . $e->getMessage() . PHP_EOL);
        //Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      
      $bdd->commit();
      return $status;
    }
    
    public function modifier(): bool {
      $status = true;
      $bdd = Base_Donnees::acces();
      try {
        $table_connexions = Enregistrement_Connexion::source();
        $jointure =  self::source() . " AS mbr INNER JOIN " . $table_connexions
          . " AS cnx ON cnx.code_membre = mbr.code ";
        $code_sql = "UPDATE " . $jointure
        . " SET "
        . "cnx.identifiant = :identifiant"
        . ", cnx.actif = :actif, cnx.connexion = :connexion"
        . ", niveau = :niveau"
        . ", genre = :genre, prenom = :prenom, nom = :nom"
        . ", date_naissance = :date_naissance"
        . ", code_commune = :code_commune, rue = :rue"
        . ", telephone = :telephone, telephone2 = :telephone2"
        . ", courriel = :courriel"
        . ", cdb = :cdb"
        . ", num_licence = :num_licence"
        . " WHERE code = :code_membre";
        
        $requete= $bdd->prepare($code_sql);
        $identifiant = $this->membre->identifiant();
        $requete->bindParam(':identifiant', $identifiant, PDO::PARAM_STR);
        
        $actif = ($this->membre->est_actif()) ? 1: 0;
        $requete->bindParam(':actif', $actif, PDO::PARAM_INT);
        $connexion = ($this->membre->est_autorise_connecter()) ? 1: 0;
        $requete->bindParam(':connexion', $connexion, PDO::PARAM_INT, 1);
        
        $niveau = $this->membre->niveau();
        $requete->bindParam(':niveau', $this->membre->niveau, PDO::PARAM_INT);
        $requete->bindParam(':genre', $this->membre->genre, PDO::PARAM_STR);
        $requete->bindParam(':prenom', $this->membre->prenom, PDO::PARAM_STR);
        $requete->bindParam(':nom', $this->membre->nom, PDO::PARAM_STR);
        if ($this->membre->date_naissance) {
          $date_naissance = $this->membre->date_naissance->date_sql(); //$cal->formatter_date_sql($this->membre->date_naissance);
          $requete->bindParam(':date_naissance', $date_naissance, PDO::PARAM_STR);
        } else {
          $requete->bindParam(':date_naissance', $this->membre->date_naissance, PDO::PARAM_NULL);
        }
        $requete->bindParam(':code_commune', $this->membre->code_commune, PDO::PARAM_INT);
        $requete->bindParam(':rue', $this->membre->rue, PDO::PARAM_STR);
        
        $tel1 = self::formatter_telephone_table($this->membre->telephone);
        $requete->bindParam(':telephone', $tel1, PDO::PARAM_STR);
        $tel2 = self::formatter_telephone_table($this->membre->telephone2);
        $requete->bindParam(':telephone2', $tel2, PDO::PARAM_STR);
        
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
      return $status;
    }
    
    static function collecter(array $criteres_selection,
                              string $composante,
                              string $role,
                              array & $personnes = null): bool {
      $status = false;
      if (is_null($personnes)) $personnes = array();
      
      // definition de la source des donnees
      $table_membres = self::source();
      $table_connexions = Enregistrement_Connexion::source();
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
      
      $jointure = " INNER JOIN " . $table_communes . " ON " . $table_communes . ".code = " . $table_membres . ".code_commune INNER JOIN " . $table_connexions . " ON " . $table_connexions . ".code_membre = " . $table_membres . ".code ";
      $join_cnx = false;
      
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
          elseif ($cle == 'cdb')
            $critere = $critere . $operateur . " cdb = '" . $valeur . "' "; // PCh 15-apr-2020 . (($valeur == 2) ? 0 : 1) . "' ";
          elseif ($cle == 'niv' && $valeur > 0)
              $critere = $critere . $operateur . " niveau " . (($valeur >= 2) ? ">=" : "<") . "2 ";
          else
            echo $cle . ' ' . $valeur . '<br />';
        }
      }
      $tri =  " ORDER BY " . $table_membres . ".prenom, " . $table_membres . ".nom ";
      try {
        $bdd = Base_Donnees::acces();
        /*
        $requete = "SELECT " . $table_membres . ".code AS code, identifiant, genre, prenom, " . $table_membres . ".nom AS nom, telephone, telephone2, rue, courriel, actif, connexion, niveau, date_naissance, cdb, derniere_connexion, num_licence, " . $table_communes . ".nom AS nom_commune" . " FROM " . $source . " INNER JOIN " . $table_communes . " ON " . $table_communes . ".code = " . $table_membres . ".code_commune " . $critere . $tri;
         */
        
        $requete = "SELECT " . $table_membres . ".code AS code, " . $table_connexions . ".identifiant AS identifiant, genre, prenom, " . $table_membres . ".nom AS nom, telephone, telephone2, rue, courriel, " . $table_connexions . ".actif AS actif, " . $table_connexions . ".connexion AS connexion, niveau, date_naissance, cdb, date_connexion, num_licence, " . $table_communes . ".nom AS nom_commune" . " FROM " . $source . $jointure . $critere . $tri;
        //print(PHP_EOL . $requete . PHP_EOL);
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
          $personne->def_identifiant($donnee->identifiant);
          $personne->def_actif($donnee->actif);
          $personne->def_autorise_connecter($donnee->connexion);
          $personne->niveau = $donnee->niveau;
          if ($donnee->date_naissance)
            $personne->date_naissance = new Instant($donnee->date_naissance);
          $personne->def_chef_de_bord($donnee->cdb);
          //if ($donnee->date_connexion)
          //  $personne->date_derniere_connexion =  new Instant($donnee->derniere_connexion);
          $personne->num_licence = $donnee->num_licence;
          
          $personnes[$personne->code()] = $personne;
          $status = true;
        }
      
      } catch (PDOException $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $status;
    }
  }
  // ==========================================================================
?>
