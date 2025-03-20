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
 * description : Definition de la classe Collecteur_Equipe_Permanence
 * utilisation : php - require_once <chemin_vers_ce_fichier_php>
 * dependances :
 * - code de la composante 'Permanence' dans la base de donnees
 * - code du role 'responsable' dans la base de donnees
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
class Collecteur_Equipe_Permanence {
  
  /*
   * code de la structure organisationnelle dans la base de donnees
   */
  private static int $code_equipe_permanence = 11;
  private static int $code_role_responsable = 13;
  
  public static function collecte_codes_membres_equipe(array & $codes_membre): bool {
    $fait = false;
    $codes_membre = [];
    $fait = Enregistrement_Entite_Organisationnelle::collecter_code_membres(self::$code_equipe_permanence,
                                                                            $codes_membre);
    return $fait;
  }
  
  public static function code_responsable(): int {
    $code_resp = 0;
    $codes_resp = [];
    $ok = Enregistrement_Entite_Organisationnelle::collecter_codes_membres_role(self::$code_equipe_permanence,
                                                                                self::$code_role_responsable,
                                                                                $codes_resp);
    if ($ok && count($codes_resp) > 0)
      $code_resp = $codes_resp[0];
    return $code_resp;
  }

  public static function effectif_equipe_permanence(): int {
    $effectif = Enregistrement_Entite_Organisationnelle::nombre_membres(self::$code_equipe_permanence);
    return $effectif;
  }
  
}
// ============================================================================
?>

