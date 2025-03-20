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
 * description : Test unitaire de la classe Collecteur_Equipe_Permanence
 * utilisation : phpunit --testdox <chemin_vers_ce_fichier_php>
 * dependances :
 * - enregistrement present dans la table de la base de donnees de test
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

use PHPUnit\Framework\TestCase;

// ----------------------------------------------------------------------------
// --- Classes de l'environnement de test

// --- Acces a la base de donnees dediee au test
include_once './base_donnees.php';

// --- Classes de l'application
set_include_path('./../../../');

include_once('php/utilitaires/definir_locale.php');

// --- Classe sous test
require_once('php/collecteur/collecteur_equipe_permanence.php');

// ============================================================================
/**
 * Test case.
 */
final class Collecteur_Equipe_PermanenceTest extends TestCase {
  
  private static ?PDO $bdd = null;

  public static function setUpBeforeClass(): void {
    print("Connection to the database" . PHP_EOL);
    self::$bdd = Base_Donnees::acces();
    self::creer_donnees();
    self::generer_donnees_test();
  }

  public static function tearDownAfterClass(): void {
    print("Delete connection handler" . PHP_EOL);
    self::$bdd = null;
  }
  
  private static function creer_donnees(): void {}
  
  private static function generer_donnees_test(): void {}
  
  public function testCollecteCodesMembresEquipePermanence(): void {
    $codes_membre = array();
    $fait = Collecteur_Equipe_Permanence::collecte_codes_membres_equipe($codes_membre);
    $this->assertTrue($fait);
    print_r($codes_membre);
  }
  
  public function testCodeResponsable(): void {
    $resultat = Collecteur_Equipe_Permanence::code_responsable();
    $this->assertEquals(6, $resultat);
  }
  
  public function testEffectifEquipePermanence(): void {
    $resultat = Collecteur_Equipe_Permanence::effectif_equipe_permanence();
    print("Effectif : " . $resultat . PHP_EOL);
    $this->assertFalse($resultat === 0);
  }
  
}
// ============================================================================
?>
