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
 * description : Tests fonctionnels unitaires de la classe Regime_Ouverture
 * utilisation : phpunit --testdox <chemin_vers_ce_fichier_php>
 * dependances :
 * - 
 * utilise avec :
 * - PHP 8.2 et PHPUnit 9.5 sur macOS 13.6
 * ----------------------------------------------------------------------------
 * creation : 19-mar-2025 pchevaillier@gmail.com
 * revision :
 * ----------------------------------------------------------------------------
 * commentaires :
 * - en evolution
 * attention :
 * -
 * a faire :
 * - a completer
 * ============================================================================
 */
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

set_include_path('./../../..');
include_once('php/utilitaires/definir_locale.php');

require_once 'php/metier/calendrier.php';
require_once 'php/metier/regime_ouverture.php';

final class Regime_OuvertureTest extends TestCase {

  public function testDefinirCreneaux(): void {
    $regime = new Regime_Diurne(1);
    $regime->heure_ouverture = new DateInterval('PT9H0M0S');
    $regime->heure_fermeture = new DateInterval('PT21H0M0S');
    
    //echo ' Lever ', $lever->format('d-m-Y H:i:s')  . PHP_EOL;
    $j = new Instant("2025-03-30 00:00:00");
    $lat = 48.3489;
    $long = -4.68248;
    $creneaux = $regime->definir_creneaux($j, $lat, $long);
    print("premier creneau : " . $creneaux[0]->debut()->heure_texte());
  }
  
}
// ===========================================================================
?>
