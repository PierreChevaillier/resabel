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
 * description : Definition de la classe Collecteur_Permanence
 * utilisation : php - require_once <chemin_vers_ce_fichier_php>
 * dependances :
 * - code de la composante 'Permanence' dans la base de donnees
 * - code du role 'responsable' dans la base de donnees
 * ----------------------------------------------------------------------------
 * creation : 09-oct-2024 pchevaillier@gmail.com
 * revision : 20-feb-2025 pchevaillier@gmail.com
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * -
 * a faire :
 * -
 * ============================================================================
 */
declare(strict_types=1);

require_once 'php/metier/permanence.php';
require_once 'php/bdd/enregistrement_permanence.php';

// ============================================================================
class Collecteur_Permanence {
  
  public static function calendrier_est_vide(): bool {
    $condition = false;
    $derniere_permanence = Enregistrement_Permanence::recherche_derniere();
    $condition = is_null($derniere_permanence);
    return $condition;
  }
  
  public static function derniere_permanence_est_avant_cette_semaine(): bool {
    $condition = false;
    $derniere_permanence = Enregistrement_Permanence::recherche_derniere();
    if (!is_null($derniere_permanence)) {
      $perm = null;
      Permanence::cette_semaine($perm);
      $jour_perm = $perm->jour();
      $jour_derniere = $derniere_permanence->jour();
      $condition = $jour_perm->est_apres($jour_derniere);
    }
    return $condition;
  }
  
  public static function premiere_permanence_extension_calendrier(): Permanence {
    $premiere = null;
    if (self::calendrier_est_vide() || self::derniere_permanence_est_avant_cette_semaine()) {
      $premiere = null;
      Permanence::cette_semaine($premiere);
    } else {
      $perm = Enregistrement_Permanence::recherche_derniere();
      $premiere = $perm->prochaine();
    }
    return $premiere;
  }
  
  public static function derniere_permanence_extension_calendrier(Permanence $premiere,
                                                                  int $nb_semaines): Permanence {
    //$nb_semaines = self::effectif_equipe_permanence();
    $j = $premiere->jour();
    $duree_extention = new DateInterval('P' . $nb_semaines * 7 . 'D');
    $jour_derniere = $j->add($duree_extention);
    $derniere = new Permanence($jour_derniere->numero_semaine(),
                               $jour_derniere->annee_semaine());
    return $derniere;
  }
  
  public static function dates_extension_calendrier(int $nb_semaines,
                                                    string & $date_texte_premiere,
                                                    string & $date_texte_derniere): void {
    $premiere = self::premiere_permanence_extension_calendrier();
    $date_texte_premiere = $premiere->jour_texte();
    $derniere = self::derniere_permanence_extension_calendrier($premiere, $nb_semaines);
    $date_texte_derniere = $derniere->jour_texte();
    return;
  }
}
// ============================================================================
?>

