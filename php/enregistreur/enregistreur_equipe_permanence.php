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
 * description : Definition de la classe Enregistreur_Equipe_Permanence
 * utilisation : php - require_once <chemin_vers_ce_fichier_php>
 * dependances :
 * - codes composante 'permanence' et role 'membre' fans la base de donnees
 * ----------------------------------------------------------------------------
 * creation : 20-feb-2025 pchevaillier@gmail.com
 * revision :
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

require_once 'php/bdd/enregistrement_struct_orga.php';

// ============================================================================
class Enregistreur_Equipe_Permanence {
  
  public static int $code_equipe_permanence = 11;
  public static int $code_role_membre = 7;
  
  static function ajoute_personne(int $rang,
                                  int $code_membre): bool {
    $fait = false;
    $operation = "+ 1";
    
    $fait = Enregistrement_Entite_Organisationnelle::decaler_rang(self::$code_equipe_permanence,
                                                                  $rang - 1,
                                                                  $operation);
    if ($fait) {
      $fait = Enregistrement_Entite_Organisationnelle::ajouter_personne(self::$code_equipe_permanence,
                                                                        self::$code_role_membre,
                                                                        $code_membre,
                                                                        $rang);
    }
    return $fait;
  }
  
  static function supprime_personne(int $rang,
                                    int $code_membre): bool {
    $fait = false;

    $fait = Enregistrement_Entite_Organisationnelle::supprimer_personne(self::$code_equipe_permanence,
                                                                        $code_membre);
    if ($fait) {
      $operation = "- 1";
      $fait = Enregistrement_Entite_Organisationnelle::decaler_rang(self::$code_equipe_permanence,
                                                                    $rang,
                                                                    $operation);
    }
    return $fait;
  }
}
// ============================================================================
?>

