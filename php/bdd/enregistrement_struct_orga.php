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
 * description : Acces aux informations stockees en base de donnees
 *               sur la structure organisationnelle : composantes, roles...
 * utilisation : php require_once <chemin_vers_ce_fichier_php>
 * dependances :
 * ----------------------------------------------------------------------------
 * creation : 25-mai-2019 pchevaillier@gmail.com
 * revision : 09-oct-2024 pchevaillier@gmail.com + collecter_codes_membre
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * -
 * a faire :
 * -
 * lister les roles assures par une personne au sein de differentes composantes
 * - SELECT * FROM `rsbl_roles_membres` INNER JOIN rsbl_roles ON rsbl_roles.code = rsbl_roles_membres.code_role  INNER JOIN rsbl_composantes ON rsbl_composantes.code = rsbl_roles_membres.code_composante WHERE code_membre = 101 ORDER BY rsbl_composantes.nom
 * ============================================================================
 */

require_once 'php/metier/struct_orga.php';
require_once 'php/metier/membre.php';
require_once 'php/bdd/base_donnees.php';

// ============================================================================
class Enregistrement_Composante {
  
  static function source(): string {
    return Base_Donnees::$prefix_table . 'composantes';
  }
  
  //private $composante = null;
  //public function composante() { return $this->composante; }
  //public function def_composante($composante) { $this->composante = $composante; }
  
  static function collecter(int $code_club, array & $composantes): bool {
    $status = false;
    try {
      $bdd = Base_Donnees::acces();
      $requete = "SELECT * FROM " . self::source() . " WHERE code_club = " . $code_club . " ORDER BY nom";
      $resultat = $bdd->query($requete);
      while ($donnee = $resultat->fetch(PDO::FETCH_OBJ)) {
        $composante= new Composante($donnee->code);
        $composante->def_genre($donnee->genre);
        $composante->def_nom_court($donnee->nom_court);
        $composante->def_nom($donnee->nom);
        $composante->def_courriel_contact($donnee->courriel_contact);
        $composante->def_liste_diffusion($donnee->liste_diffusion);
      
        $composantes[$composante->code()] = $composante;
      }
    } catch (PDOException $e) {
      Base_Donnees::sortir_sur_exception(self::source(), $e);
    }
    return $status;
  }
}
  
// ============================================================================
class Enregistrement_Entite_Organisationnelle {
    
  static function collecter(array & $entite_orga): bool {
    $status = false;
    try {
      $bdd = Base_Donnees::acces();
      $requete = "SELECT RC.code_composante, R.nom_masculin, R.nom_feminin, RC.code_role, RC.rang, RM.code_membre, M.genre, M.prenom, M.nom, M.telephone, M.courriel FROM `rsbl_roles_composantes` AS RC INNER JOIN rsbl_roles_membres AS RM on (RC.code_composante = RM.code_composante AND RC.code_role = RM.code_role) INNER JOIN rsbl_membres AS M ON RM.code_membre = M.code INNER JOIN rsbl_roles AS R ON R.code = RC.code_role ORDER BY RC.code_composante, RC.rang, RM.rang, M.prenom, M.nom";
      $resultat = $bdd->query($requete);
      while ($donnee = $resultat->fetch(PDO::FETCH_OBJ)) {
        $role_membre = new Role_Membre();
        
        // Informations sur la personne qui a ce role dans la composante
        $membre = new Membre($donnee->code_membre);
        $membre->def_genre($donnee->genre);
        $membre->def_prenom($donnee->prenom);
        $membre->def_nom($donnee->nom);
        $membre->def_telephone($donnee->telephone);
        $membre->def_courriel($donnee->courriel);
        $role_membre->membre = $membre;
        
        // La composante dans laquelle la personne a un role
        $rc =  new Role_Composante();
        $rc->composante = new Composante($donnee->code_composante);
        $role = new Role($donnee->code_role);
        $role->nom_feminin = $donnee->nom_feminin;
        $role->nom_masculin = $donnee->nom_masculin;
        
        $rc->role = $role;
        
        $rc->rang_role = $donnee->rang;
        
        $role_membre->role_composante = $rc;
      
        $entite_orga[] = $role_membre;
      }
    } catch (PDOException $e) {
      Base_Donnees::sortir_sur_exception('rsbl_roles_composantes', $e);
    }
    return $status;
  }
  
  static function collecter_code_membres(int $code_composante,
                                         array & $codes_membre): bool {
    $fait = false;
    try {
      $bdd = Base_Donnees::acces();
      $requete = "SELECT code_membre FROM `rsbl_roles_membres` WHERE code_composante = "
      . $code_composante . " ORDER BY rang";
      $resultat = $bdd->query($requete);
      while ($donnee = $resultat->fetch(PDO::FETCH_OBJ)) {
        $codes_membre[] = $donnee->code_membre;
      }
      $fait = true;
    } catch (PDOException $e) {
      Base_Donnees::sortir_sur_exception('rsbl_roles_membres', $e);
    }
    return $fait;
  }
}
// ============================================================================
?>
