<?php
/* ============================================================================
 * Resabel - systeme de REServAtion de Bateau En Ligne
 * Copyright (C) 2024 Pierre Chevaillier
 * contact: pchevaillier@gmail.com 70 allee de Broceliande, 29200 Brest, France
 * -----------------------------------------------------------------------------
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
 * description : actions sur la table rsbl_connexion
 * utilisation : php - require_once <chemin_vers_ce_fichier_php>
 * dependances :
 * - structure table rsbl_connexion
 * ----------------------------------------------------------------------------
 * creation : 12-nov-2023 pchevaillier@gmail.com
 * revision : 28-aug-2024 pchevaillier@gmail.com + activer_compte
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * -
 * a faire :
 * -
 * ============================================================================
*/

require_once 'php/metier/connexion.php';
require_once 'php/metier/calendrier.php';

// ============================================================================
class Erreur_Identifiant_Connexion extends Exception { }
class Erreur_Mot_Passe_Connexion extends Exception { }

// ============================================================================
class Enregistrement_Connexion {
  
  static function source(): string {
    return Base_Donnees::$prefix_table . 'connexions';
  }
  
  // Objet Connexion associe (access public)
  private ?Connexion $connexion = null;
  public function connexion(): ?Connexion { return $this->connexion; }
  public function def_connexion(Connexion $cnx): void { $this->connexion = $cnx; }

  /*
   *
   */
  public function verifier_identite(string $mot_passe_clair): ? bool {
    $identification_ok = null;
    $bdd = Base_Donnees::acces();
    $requete = $bdd->prepare("SELECT code_membre, mot_passe, connexion, actif, date_connexion FROM "
                             . self::source()
                             . " WHERE identifiant = :identifiant LIMIT 1");
    $id = $this->connexion->identifiant();
    $requete->bindParam(':identifiant', $id, PDO::PARAM_STR);
    try {
      $requete->execute();
      if ($donnee = $requete->fetch(PDO::FETCH_OBJ)) {
        $this->connexion->def_mot_de_passe($donnee->mot_passe);
        $mot_passe_ok = $this->connexion->verifier_mot_passe($mot_passe_clair);
        if (!$mot_passe_ok) {
          throw new Erreur_Mot_Passe_Connexion();
          //return $identification_ok; // pas executee
        } else {
          $this->connexion->def_code_membre($donnee->code_membre);
          $this->connexion->def_est_autorise($donnee->connexion);
          $this->connexion->def_est_compte_actif($donnee->actif);
          if ($donnee->date_connexion)
            $this->connexion->def_date_derniere_connexion(new Instant($donnee->date_connexion));
          $identification_ok = true;
        }
      } else {
        print(PHP_EOL . "identifiant : " . $id . PHP_EOL);
        throw new Erreur_Identifiant_Connexion();
        //return $identification_ok;
      }
    } catch (PDOException $e) {
     Base_Donnees::sortir_sur_exception(self::source(), $e);
    }
    $requete->closeCursor();
    return $identification_ok;
  }
    
    
  public function modifier_date_derniere_connexion():void {
    $bdd = Base_Donnees::acces();
    $nouvelle_date = $this->connexion->date_derniere_connexion()->date_heure_sql();
    $code = $this->connexion->code_membre();
    $code_sql = 'UPDATE ' . self::source()
      . ' SET date_connexion = "' . $nouvelle_date . '" WHERE code_membre = :code';
    
    try {
      $requete= $bdd->prepare($code_sql);
      $requete->bindParam(':code', $code, PDO::PARAM_INT);
      $requete->execute();
    } catch (PDOexception $e) {
      die("Erreur Mise a jour " . self::source() . " date derniere connexion pour " . $code . " : ligne " . $e->getLine() . " :<br /> ". $e->getMessage());
    }
  }
  

//    public function verifier_identifiant_unique(string $identifiant): bool {
  public function verifier_identifiant_unique(string $identifiant): bool {
    $unique = false;
    try {
      $bdd = Base_Donnees::acces();
      $requete= $bdd->prepare("SELECT COUNT(*) AS n FROM " . self::source() . " WHERE identifiant = :identifiant and code_membre != :code");
      $requete->bindParam(':identifiant', $identifiant, PDO::PARAM_STR);
      $code = $this->connexion->code_membre();
      $requete->bindParam(':code', $code, PDO::PARAM_INT);
      $requete->execute();
      if ($resultat = $requete->fetch(PDO::FETCH_OBJ))
        $unique = ($resultat->n == 0);
    } catch (PDOException  $e) {
      Base_Donnees::sortir_sur_exception(self::source(), $e);
    }
    return $unique;
  }
  
  public function modifier_mot_de_passe(): bool {
    $status = true;
    if (is_null($this->connexion)) return false;
    $date_modif = (Calendrier::maintenant())->date_heure_sql();
    $code_sql = 'UPDATE ' . self::source()
      . ' SET mot_passe = "' . $this->connexion->mot_de_passe()
      . '", date_mot_passe = "' . $date_modif
      . '" WHERE code_membre = '. $this->connexion->code_membre();
    try {
      $bdd = Base_Donnees::acces();
      $n = $bdd->exec($code_sql);
      $status = ($n == 1);
    } catch (PDOException  $e) {
      Base_Donnees::sortir_sur_exception(self::source(), $e);
    }
    return $status;
  }
  
  public function activer_compte(int $nouveau_statut): bool {
    $status = true;
    if (is_null($this->connexion)) return false;
    $date_modif = (Calendrier::maintenant())->date_heure_sql();
    $code_sql = 'UPDATE ' . self::source()
      . ' SET actif = "' . $nouveau_statut
      . '", connexion = "' . $nouveau_statut
      . '", date_actif = "' . $date_modif
      . '" WHERE code_membre = '. $this->connexion->code_membre();
    try {
      $bdd = Base_Donnees::acces();
      $n = $bdd->exec($code_sql);
      $status = ($n == 1);
    } catch (PDOException  $e) {
      Base_Donnees::sortir_sur_exception(self::source(), $e);
    }
    return $status;
  }
 }
// ============================================================================
?>
