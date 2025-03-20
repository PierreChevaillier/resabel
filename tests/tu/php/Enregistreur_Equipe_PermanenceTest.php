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
 * description : Test unitaire de la classe Enregistreur_Equipe_Permanence
 * utilisation : phpunit --testdox <chemin_vers_ce_fichier_php>
 * dependances :
 * -
 * ----------------------------------------------------------------------------
 * creation : 03-mar-2024 pchevaillier@gmail.com
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
require_once('php/enregistreur/enregistreur_equipe_permanence.php');

// --- autres classes

// ============================================================================
/**
 * Test case.
 */
final class Enregistreur_Equipe_PermanenceTest extends TestCase {
  
  private static ?PDO $bdd = null;

  private $equipe = [];
  
  public static function setUpBeforeClass(): void {
    print("Connection to the database" . PHP_EOL);
    self::$bdd = Base_Donnees::acces();
  }

  public static function tearDownAfterClass(): void {
    print("Delete connection handler" . PHP_EOL);
    self::$bdd = null;
  }

  private function creer_donnees(): void {
    $this->equipe = [101, 6, 2507];
  }
  
  private function generer_donnees_test(): void {
    $source = Base_Donnees::$prefix_table . 'roles_membres';
    $code_sql = 'INSERT INTO ' . $source
      . ' (code_membre, code_role, code_composante, rang) VALUES(:code_membre, '
      . Enregistreur_Equipe_Permanence::$code_role_membre
      . ', ' . Enregistreur_Equipe_Permanence::$code_equipe_permanence
      . ', :rang)';
    $requete = self::$bdd->prepare($code_sql);

    for ($i = 0; $i < count($this->equipe); $i++) {
      $requete->bindParam(':code_membre', $this->equipe[$i], PDO::PARAM_INT);
      $rang = $i + 1;
      $requete->bindParam(':rang', $rang, PDO::PARAM_INT);
      $requete->execute(); // ["" => , "rang" => $i+1]);
    }
  }
  
  protected function setUp(): void {
    parent::setUp();
    $this->vider_equipe();
    $this->creer_donnees();
    $this->generer_donnees_test();
  }

  private function vider_equipe() : void {
    $source = Base_Donnees::$prefix_table . 'roles_membres';
    $code_sql = 'DELETE FROM ' . $source
      . ' WHERE code_composante = ' . Enregistreur_Equipe_Permanence::$code_equipe_permanence;
    self::$bdd->exec($code_sql);
  }

  private function effectif_equipe(): ?int {
    $nombre = null;
    $source = Base_Donnees::$prefix_table . 'roles_membres';
    $code_sql = 'SELECT COUNT(*) AS n FROM ' . $source
      . ' WHERE code_composante = :code_equipe' ;
    $requete = self::$bdd->prepare($code_sql);
    
    $requete->bindParam(':code_equipe', Enregistreur_Equipe_Permanence::$code_equipe_permanence, PDO::PARAM_INT);
    $requete->execute();
    if ($resultat = $requete->fetch(PDO::FETCH_OBJ))
      $nombre = $resultat->n;
    return $nombre;
  }
  
  private function rang_personne(int $code_membre): ?int {
    $rang = null;
    $source = Base_Donnees::$prefix_table . 'roles_membres';
    $code_sql = 'SELECT rang FROM ' . $source
      . ' WHERE code_composante = ' . Enregistreur_Equipe_Permanence::$code_equipe_permanence
      . ' AND code_membre = ' . $code_membre . ' LIMIT 1';
    $requete = self::$bdd->prepare($code_sql);
    $requete->execute();
    if ($resultat = $requete->fetch(PDO::FETCH_OBJ))
      $rang = $resultat->rang;
    return $rang;
  }
  
    
  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown(): void {
    $this->vider_equipe();
    parent::tearDown();
  }
  
  public function testSupprimePersonneRang(): void {
    $n = count($this->equipe);
    
    for ($j = 0; $j < $n; $j++) {
      $this->vider_equipe();
      $this->generer_donnees_test();
      
      $n1 = $this->effectif_equipe();
      $m1 = $this->equipe[$j];
      $r1 = $this->rang_personne($m1);
      print(PHP_EOL . "\t personne " . $m1 . " - rang " . $r1 . PHP_EOL);
      
      $fait = Enregistreur_Equipe_Permanence::supprime_personne($r1, $m1); // operation sous test
      $this->assertTrue($fait);
      
      $n2 = $this->effectif_equipe();
      $this->assertEquals($n1 - 1, $n2);
      
      for ($i = 0; $i < $r1 - 1; $i++) {
        $r2 = $this->rang_personne($this->equipe[$i]);
        $this->assertEquals($i+1, $r2);
      }
      
      $r2 = $this->rang_personne($m1);
      $this->assertNull($r2);
      
      for ($i = $r1; $i < $n; $i++) {
        $r2 = $this->rang_personne($this->equipe[$i]);
        $this->assertEquals($i, $r2);
      }
    }
  }

  public function testAjoutePersonne(): void {
    $this->vider_equipe();
    $r1 = 1;
    $m1 = $this->equipe[0];
    $fait = Enregistreur_Equipe_Permanence::ajoute_personne($r1, $m1); // operation sous test
    $this->assertTrue($fait);
    
    $r2 = $this->rang_personne($m1);
    $this->assertEquals($r1, $r2);

    $r1 = 2;
    $m1 = $this->equipe[2];
    $fait = Enregistreur_Equipe_Permanence::ajoute_personne($r1, $m1); // operation sous test
    $this->assertTrue($fait);
    
    $r2 = $this->rang_personne($m1);
    $this->assertEquals($r1, $r2);
    $r2 = $this->rang_personne($this->equipe[0]);
    $this->assertEquals(1, $r2);
    
    $r1 = 2;
    $m1 = $this->equipe[1];
    $fait = Enregistreur_Equipe_Permanence::ajoute_personne($r1, $m1); // operation sous test
    $this->assertTrue($fait);
    
    $r2 = $this->rang_personne($m1);
    $this->assertEquals($r1, $r2);
    $r2 = $this->rang_personne($this->equipe[0]);
    $this->assertEquals(1, $r2);
    $r2 = $this->rang_personne($this->equipe[2]);
    $this->assertEquals(3, $r2);

  }
}
// ============================================================================
?>
