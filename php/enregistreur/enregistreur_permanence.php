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
 * - code de la composante 'Permanence' dans la base de donnees
 * - code du role 'membre' dans la base de donnees
 * ----------------------------------------------------------------------------
 * creation : 09-oct-2024 pchevaillier@gmail.com
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

require_once 'php/metier/permanence.php';
require_once 'php/metier/calendrier.php';
require_once 'php/metier/personne.php';
require_once 'php/bdd/enregistrement_permanence.php';

// ============================================================================

class Enregistreur_Permanence {
  
  public static function ajoute_permanences(array & $codes_membre): bool {
    $fait = false;
    $code_membre = 0;
    
    $enreg = new Enregistrement_Permanence();
    $perm = $enreg->recherche_derniere();
    $j = Calendrier::date_jour_semaine(6, $perm->semaine(), $perm->annee());
    
    echo "derniere : " . $perm->annee() . " " . $perm->semaine() . PHP_EOL;
    echo "Jour de la perm :" . $j->date_texte() . PHP_EOL;
    
    foreach ($codes_membre as $code_membre) {
      
      $jour_perm = $j->add(new DateInterval("P7D"));
      $j = $jour_perm;
      
      $responsable_perm = new Personne($code_membre);
      $num_semaine = $jour_perm->numero_semaine();
      $annee = intval($jour_perm->format('Y'));
      
      $perm = new Permanence($num_semaine, $annee);
      $perm->def_responsable($responsable_perm);
      echo $perm->code_responsable() . ' : ' .  $jour_perm->date_texte() . ' => ' . $perm->semaine() . ' / ' . $perm->annee() . PHP_EOL;
      
      $enreg->def_permanence($perm);
      $enreg->enregistre();
      
    }
    $fait = true;
    return $fait;
  }
}
// ============================================================================
?>

