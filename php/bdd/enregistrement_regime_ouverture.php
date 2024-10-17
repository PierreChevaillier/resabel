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
 * description : classe Enregistrement_Regime_Ouverture (d'un site d'activite)
 * utilisation : php - require_once <chemin_vers_ce_fichier_php>
 * dependances :
 * - aucune
 * ----------------------------------------------------------------------------
 * creation : 01-jul-2019 pchevaillier@gmail.com
 * revision : 27-dec-2019 pchevaillier@gmail.com impact refonte Calendrier
 * revision : 13-oct-2024 pchevaillier@gmail.com jour_uniquement
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * -
 * a faire :
 * -
 * ============================================================================
 */

require_once 'php/metier/regime_ouverture.php';
require_once 'php/metier/calendrier.php';

// ----------------------------------------------------------------------------
class Erreur_Type_Regime_Ouverture extends Exception { }

// ----------------------------------------------------------------------------
class Enregistrement_Regime_Ouverture {
  static function source(): string {
    return Base_Donnees::$prefix_table . 'regimes_ouverture';
  }
  
  public static function creer(int $code): ?Regime_Ouverture {
    $regime = null;
    try {
      $bdd = Base_Donnees::acces();
      $code_sql = "SELECT code, code_type, nom, heure_ouverture, heure_fermeture, duree_seance, de_jour_uniquement, decalage_heure_hiver FROM " . self::source() . " WHERE code = "  . $code . " ORDER BY code, jour_semaine";
      //echo '<p>', $code_sql, '</p>';
      $requete = $bdd->query($code_sql);
      while ($donnee = $requete->fetch(PDO::FETCH_OBJ)) {
        // Il faut trouver le type de l'objet a instancier (pas terrible...)
        if ($donnee->code_type == 1) {
          if (!isset($regime)) {
            $regime = new Regime_Diurne($code);
          }
          // 1 seul enregistrement avec jour_semaine = 0
          $regime->heure_ouverture = Calendrier::creer_DateInterval_depuis_time_sql($donnee->heure_ouverture);
          $regime->heure_fermeture = Calendrier::creer_DateInterval_depuis_time_sql($donnee->heure_fermeture);
          $regime->decalage_heure_hiver = Calendrier::creer_DateInterval_depuis_time_sql($donnee->decalage_heure_hiver);
          
        } elseif ($donnee->code_type == 2) {
          if (!isset($this->regime)) {
            $regime = new Regime_Hebdomadaire($code);
          }
          // si pas encore d'entree pour ce jour : creer l'entree avec liste de plages horaires vides
          // pour l'entree du jour, creer une plage horaire et l'ajouter a la liste pour le sour de la semaine
        } else {
          $requete->closeCursor();
          throw new Erreur_Type_Regime_Ouverture();
        }
        
        $regime->jour_uniquement = ($donnee->de_jour_uniquement == 1);
        $regime->def_nom($donnee->nom);
        $regime->duree_seance = Calendrier::creer_DateInterval_depuis_time_sql($donnee->duree_seance);
      }
    } catch (PDOexception $e) {
      Base_Donnees::sortir_sur_exception(self::source(), $e);
    }
    $requete->closeCursor();
    return $regime;
  }

}
// ============================================================================
?>
