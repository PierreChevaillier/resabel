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
 * description : Definition de la classe Enregistreur_Permanence
 * utilisation : php - require_once <chemin_vers_ce_fichier_php>
 * dependances :
 * - aucune
 * ----------------------------------------------------------------------------
 * creation : 09-oct-2024 pchevaillier@gmail.com
 * revision : 17-fev-2025 pchevaillier@gmail.com
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
require_once 'php/metier/calendrier.php';
require_once 'php/metier/personne.php';
require_once 'php/bdd/enregistrement_permanence.php';

// ============================================================================

class Enregistreur_Permanence {
  
  public static function etend_calendrier(array & $codes_membres): int {
    $code_retour = 0;
    //$codes_membres = array();
    //Collecteur_Permanence::collecte_codes_membres_equipe($codes_membres);
    if (count($codes_membres) == 0) {
      $code_retour = 1;
    } else {
      $fait = self::ajoute_permanences($codes_membres);
      if (!$fait) $code_retour = 2;
    }
    return $code_retour;
  }
  
  public static function ajoute_permanences(array & $codes_membre): bool {
    $fait = false;
    $code_membre = 0;
    
    $enreg = new Enregistrement_Permanence();

    $perm = Collecteur_Permanence::premiere_permanence_extension_calendrier();
    foreach ($codes_membre as $code_membre) {
      
      $responsable_perm = new Personne($code_membre);
      $perm->def_responsable($responsable_perm);
      echo $perm->code_responsable() . ' : ' .  $perm->jour_texte()
        . ' => ' . $perm->semaine() . ' / ' . $perm->annee() . PHP_EOL;
      
      $enreg->def_permanence($perm);
      //$enreg->enregistre();
      
      $perm = $perm->prochaine();
      
    }
    $fait = true;
    return $fait;
  }
  
  public static function permute_responsable(int $semaine_perm1,
                                             int $annee_perm1,
                                             int $semaine_perm2,
                                             int $annee_perm2): bool {
    $faisable = Permanence::semaine_valide($semaine_perm1, $annee_perm1)
      && Permanence::semaine_valide($semaine_perm2, $annee_perm2);
    if (!$faisable) return false;
    
    $code_resp1 = Enregistrement_Permanence::lire_code_responsable($semaine_perm1, $annee_perm1);
    if (is_null($code_resp1)) return false;
    
    print("code resp1 :" . $code_resp1 . PHP_EOL);
    $enreg1 = new Enregistrement_Permanence();
    $perm1 = new Permanence($semaine_perm1, $annee_perm1);
    $enreg1->def_permanence($perm1);
    $resp1 = new Personne($code_resp1);
    $perm1->def_responsable($resp1);
    
    $code_resp2 = Enregistrement_Permanence::lire_code_responsable($semaine_perm2, $annee_perm2);
    if (is_null($code_resp2)) return false;
    
    print("code resp2 :" . $code_resp2 . PHP_EOL);
    $enreg2 = new Enregistrement_Permanence();
    $perm2 = new Permanence($semaine_perm2, $annee_perm2);
    $enreg2->def_permanence($perm2);
    $resp2 = new Personne($code_resp2);
    $perm2->def_responsable($resp2);
    
    $fait = false;
    $bdd = Base_Donnees::acces();
    $bdd->beginTransaction();
    {
      $perm1_ok = $enreg1->change_responsable($code_resp2);
      if ($perm1_ok) {
        $perm2_ok = $enreg2->change_responsable($code_resp1);
        if (!$perm2_ok)
          $bdd->rollback();
      } else {
        $bdd->rollback();
      }
    }
    $bdd->commit();
    $fait = $perm1_ok && $perm2_ok;
    return $fait;
  }
  
  public static function change_responsable(int $semaine,
                                            int $annee,
                                            int $code_nouveau_responsable): bool {
    $faisable = Permanence::semaine_valide($semaine, $annee)
      && ($code_nouveau_responsable > 0);
    if (!$faisable) return false;
    
    $enreg = new Enregistrement_Permanence();
    $perm = new Permanence($semaine, $annee);
    $enreg->def_permanence($perm);
    
    $fait = $enreg->change_responsable($code_nouveau_responsable);
    
    return $fait;
  }
}

// ============================================================================
?>

