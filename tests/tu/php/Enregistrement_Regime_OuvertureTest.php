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
 * description : Tests fonctionnels unitaires de la classe Enregistrement_Permanence
 * utilisation : phpunit --testdox <chemin_vers_ce_fichier_php>
 * dependances :
 * - structure table
 * utilise avec :
 * - PHP 8.2 et PHPUnit 9.5 sur macOS 13.6
 * ----------------------------------------------------------------------------
 * creation : 29-oct-2025 pchevaillier@gmail.com
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
// --- Classes de l'enviromment de test
include_once './base_donnees.php';

// --- Classes de l'application
set_include_path('./../../../');

// classe sous test
require_once('php/bdd/enregistrement_regime_ouverture.php');

// autres classes
require_once 'php/metier/calendrier.php';
require_once 'php/metier/regime_ouverture.php';

// ============================================================================
/**
 * Test case.
 */
class Enregistrement_Regime_OuvertureTest extends TestCase {

  private static ?PDO $bdd = null;
  private static $nom_table = "regimes_ouverture";
  
  private $codes_regime = array();
  
  private $heure_ouverture_sql = '07:31:45';
  private $heure_fermeture_sql = '17:13:38';
  private $duree_seance_sql = '00:45:00';
  private $decalage_hiver_sql = '00:15:00';

  public static function setUpBeforeClass(): void {
    print("Test case: Connection to the database" . PHP_EOL);
    self::$bdd = Base_Donnees::acces();
  }

  public static function tearDownAfterClass(): void {
    print("Test case: Delete connection handler" . PHP_EOL);
    self::$bdd = null;
  }
  
  /**
   * Prepares the environment before running a test.
   */
  protected function setUp(): void {
    parent::setUp();
    $this->supprimer_regimes();
    $this->creer_regimes();
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown(): void {
      $this->supprimer_regimes();
    parent::tearDown();
  }

  private function creer_regimes(): void {
    $source = PREFIX_TABLE . self::$nom_table;

    $requete = self::$bdd->prepare("INSERT INTO " . $source
                                   . " (code,code_type, nom, description, heure_ouverture, heure_fermeture, duree_seance, de_jour_uniquement, decalage_heure_hiver) VALUES ("
                                   . ":code, :code_type, 'nom regime', 'description regime'"
                                   . ", '" . $this->heure_ouverture_sql
                                   . "', '" . $this->heure_fermeture_sql
                                   . "', '" . $this->duree_seance_sql
                                   . "', '1' " . ", '" . $this->decalage_hiver_sql
                                   . "')"
                                   );
    try {
      $code_regime = 101;
      $code_type = Enregistrement_Regime_Ouverture::CODE_TYPE_REGIME_DIURNE;
      $requete->bindParam(':code', $code_regime, PDO::PARAM_INT);
      $requete->bindParam(':code_type', $code_type, PDO::PARAM_INT);
      $ok = $requete->execute();
      if ($ok) $this->codes_regime[] = $code_regime;
      
      $code_regime = 102;
      $code_type = Enregistrement_Regime_Ouverture::CODE_TYPE_REGIME_JOURNALIER;
      $requete->bindParam(':code', $code_regime, PDO::PARAM_INT);
      $requete->bindParam(':code_type', $code_type, PDO::PARAM_INT);
      $ok = $requete->execute();
      if ($ok) $this->codes_regime[] = $code_regime;
      
    } catch (PDOexception $e) {
      Base_Donnees::sortir_sur_exception($source, $e);
    }
    return;
  }
  
  private function supprimer_regimes(): void {
    $source = PREFIX_TABLE . self::$nom_table;
    $requete = self::$bdd->prepare("DELETE FROM " . $source . " WHERE code = :code");
    
    foreach ($this->codes_regime as $code_regime) {
      try {
        $requete->bindParam(':code', $code_regime, PDO::PARAM_INT);
        $requete->execute();
      } catch (PDOexception $e) {
        Base_Donnees::sortir_sur_exception($source, $e);
      }
    }
    return;
  }
  
  /**
   * Teste le nom de la table source des informations
   */
  public function testSourceTableRegime(): void {
    $this->assertEquals(PREFIX_TABLE . self::$nom_table, Enregistrement_Regime_Ouverture::source());
  }

  /**
   * Teste la creation d'un Regime_Diurne
   * depuis un enregistrement en table
   * avec ses attributs correctement initialises
   */
  public function testCreationRegimeDiurne(): void {
    $code_regime = 101;
    $regime = Enregistrement_Regime_Ouverture::creer($code_regime);
    
    $this->assertTrue(is_a($regime, 'Regime_Diurne'));

    $this->assertEquals(101, $regime->code());
    $this->assertEquals('nom regime', $regime->nom());
    
    $heure = $regime->heure_ouverture->format("%H:%I:%S");
    $this->assertEquals($heure, $this->heure_ouverture_sql);
    $heure = $regime->heure_fermeture->format("%H:%I:%S");
    $this->assertEquals($heure, $this->heure_fermeture_sql);
    $heure = $regime->duree_seance->format("%H:%I:%S");
    $this->assertEquals($heure, $this->duree_seance_sql);
    $heure = $regime->decalage_heure_hiver->format("%H:%I:%S");
    $this->assertEquals($heure, $this->decalage_hiver_sql);
    
    $this->assertEquals(1, $regime->jour_uniquement);
  }
  
  /**
   * Teste la creation d'un Regime_Journalier
   * depuis un enregistrement en table
   * avec ses attributs correctement initialises
   */
  public function testCreationRegimeJournalier(): void {
    $code_regime = 102;
    $regime = Enregistrement_Regime_Ouverture::creer($code_regime);
    
    $this->assertTrue(is_a($regime, 'Regime_Journalier'));

    $this->assertEquals(102, $regime->code());
    $this->assertEquals('nom regime', $regime->nom());
    
    $heure = $regime->heure_ouverture->format("%H:%I:%S");
    $this->assertEquals($heure, $this->heure_ouverture_sql);
    $heure = $regime->heure_fermeture->format("%H:%I:%S");
    $this->assertEquals($heure, $this->heure_fermeture_sql);
    $heure = $regime->duree_seance->format("%H:%I:%S");
    $this->assertEquals($heure, $this->duree_seance_sql);
    $heure = $regime->decalage_heure_hiver->format("%H:%I:%S");
    $this->assertEquals($heure, $this->decalage_hiver_sql);

  }
}
// ==========================================================================
?>
