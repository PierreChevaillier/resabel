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
 * description : Test unitaire de la classe Entite_Organisationnelle
 * utilisation : phpunit --testdox <chemin_vers_ce_fichier_php>
 * dependances :
 * - enregistrements presents dans la table de la base de donnees de test
 * ----------------------------------------------------------------------------
 * creation : 18-feb-2025 pchevaillier@gmail.com
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
require_once('php/bdd/Enregistrement_struct_orga.php');

// ============================================================================
/**
 * Test case.
 */
final class Enregistrement_Struct_OrgaTest extends TestCase {
  
  private static ?PDO $bdd = null;

  private int $code_composante = 127; // (SQL TINY INT)
  
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
  
  private static function creer_donnees(): void {
  }
  
  private static function generer_donnees_test(): void {}
  
  protected function setUp(): void {
    $this->effacer_roles_membres_composante($this->code_composante);
    parent::setUp();
  }

  protected function tearDown(): void {
    $this->effacer_roles_membres_composante($this->code_composante);
    parent::tearDown();
  }
  
  private function ajouter_role_membre_composante(int $code_composante,
                                                  int $code_role,
                                                  int $code_membre,
                                                  int $rang): bool {
    $fait = false;
    $source = Base_Donnees::$prefix_table . "roles_membres";
    $code_sql = "INSERT INTO " . $source
      . " VALUES ("
      . $code_membre . ", "
      . $code_role . ", "
      . $code_composante . ", "
      . $rang . ")"
    ;
    $n = self::$bdd->exec($code_sql);
    $fait = ($n === 1);
    return $fait;
  }
 
  private function effacer_roles_membres_composante(int $code_composante): void {
    $source = Base_Donnees::$prefix_table . "roles_membres";
    $code_sql = "DELETE fROM " . $source
      . " WHERE code_composante = " . $code_composante;
    self::$bdd->exec($code_sql);
  }
  
  private function existe(int $code_composante,
                          int $code_role,
                          int $code_membre,
                          int $rang): bool {
    $existe = false;
    $source = Base_Donnees::$prefix_table . "roles_membres";
    $code_sql = "SELECT COUNT(*) AS n FROM " . $source
      . " WHERE code_composante = " . $code_composante
      . " AND code_role = " . $code_role
      . " AND code_membre = " . $code_membre
      . " AND rang = " . $rang
      . " LIMIT 1"
    ;
    $resultat = self::$bdd->query($code_sql);
    $donnee = $resultat->fetch(PDO::FETCH_OBJ);
    $existe = ($donnee->n === 1);
    return $existe;
  }
  
  public function testCollecteCodesMembresComposante(): void {
    $codes_membres = array();
    $fait = Enregistrement_Entite_Organisationnelle::collecter_code_membres($this->code_composante,
                                                                            $codes_membres);
    $this->assertTrue($fait); // personne dans la composante a ce stade
    $this->assertEquals(0, count($codes_membres));
    
    $code_membre = 101;
    $code_role = 1;
    $rang = 1;
    $this->ajouter_role_membre_composante($this->code_composante,
                                          $code_role,
                                          $code_membre,
                                          $rang);
    $codes_membres = [];
    $fait = Enregistrement_Entite_Organisationnelle::collecter_code_membres($this->code_composante,
                                                                            $codes_membres);
    $this->assertTrue($fait); // personne dans la componsate a ce stade
    $this->assertEquals(1, count($codes_membres));
    $this->assertEquals($code_membre, $codes_membres[0]);
    //print_r($codes_membres);
  }
  
  public function testAjouterPersonne(): void {
    $code_membre = 101;
    $code_role = 1;
    $rang = 1;
    $fait = Enregistrement_Entite_Organisationnelle::ajouter_personne($this->code_composante,
                                                                      $code_role,
                                                                      $code_membre,
                                                                      $rang);
    $this->assertTrue($fait);
    $ok = $this->existe($this->code_composante,
                        $code_role,
                        $code_membre,
                        $rang);
    $this->assertTrue($ok);
    
    $code_membre = $code_membre + 1;
    $rang = $rang + 1;
    
    $fait = Enregistrement_Entite_Organisationnelle::ajouter_personne($this->code_composante,
                                                                      $code_role,
                                                                      $code_membre,
                                                                      $rang);
    $this->assertTrue($fait);
    $ok = $this->existe($this->code_composante,
                        $code_role,
                        $code_membre,
                        $rang);
    $this->assertTrue($ok);
    
  }
  
  
  public function testSupprimerPersonne(): void {
    $code_membre = 101;
    $code_role = 1;
    $rang = 1;
    $fait = Enregistrement_Entite_Organisationnelle::ajouter_personne($this->code_composante,
                                                                      $code_role,
                                                                      $code_membre,
                                                                      $rang);
    
    $fait = Enregistrement_Entite_Organisationnelle::supprimer_personne($this->code_composante,
                                                                         $code_membre);
    $this->assertTrue($fait);
    
    $ok = $this->existe($this->code_composante,
                        $code_role,
                        $code_membre,
                        $rang);
    $this->assertFalse($ok);
  }
  
  public function testDecalerRang(): void {
    $code_membre = 101;
    $code_role = 1;
    $rang = 1;
    
    Enregistrement_Entite_Organisationnelle::ajouter_personne($this->code_composante,
                                                                      $code_role,
                                                                      $code_membre,
                                                                      $rang);
    
    $fait = Enregistrement_Entite_Organisationnelle::decaler_rang($this->code_composante,
                                                                  $rang,
                                                                  " + 1");
    $this->assertFalse($fait);
    
    $rang = $rang - 1;
    $fait = Enregistrement_Entite_Organisationnelle::decaler_rang($this->code_composante,
                                                                  $rang,
                                                                  " + 1");
    $this->assertTrue($fait);
    $ok = $this->existe($this->code_composante,
                        $code_role,
                        $code_membre,
                        2);
    $this->assertTrue($ok);
    
    $rang = 1;
    $fait = Enregistrement_Entite_Organisationnelle::decaler_rang($this->code_composante,
                                                                  $rang,
                                                                  " - 1");
    $this->assertTrue($fait);
    $ok = $this->existe($this->code_composante,
                        $code_role,
                        $code_membre,
                        1);
    $this->assertTrue($ok);
  }
}
// ============================================================================
?>
