<?php
// ==========================================================================
// contexte : Resabel - systeme de REServAtion de Bateau En Ligne
//            Tests unitaires
// description : Test unitaire de la classe Intervalle_Temporel
// copyright (c) 2023 AMP. Tous droits reserves.
// --------------------------------------------------------------------------
// utilisation : phpunit --testdox <chemin_vers_ce_fichier_php>
// dependances :
// utilise avec :
//  - depuis 2023 :
//    PHP 8.2 et PHPUnit 9.5 sur macOS 13.2 ;
// --------------------------------------------------------------------------
// creation : 06-fev-2023 pchevaillier@gmail.com
// revision :
// --------------------------------------------------------------------------
// commentaires :
// -
// attention :
// -
// a faire :
// -
// ==========================================================================
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

set_include_path('./../../..');

require_once 'php/metier/calendrier.php';
//require_once 'PHPUnit/Autoload.php';

/**
 * Instant test case.
 */
final class Intervalle_TemporelTest extends TestCase {
  /**
   * instance of the class under test
   * @var intervalle
   */
  private $intervalle;

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp(): void {
    parent::setUp();
    $this->intervalle = null;
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown(): void {
    $this->intervalle = null;
    parent::tearDown();
  }

  /**
   * Constructs the test case.
   */
  /*
  public function __construct($name = null, array $data = [], $dataName = '') {
      parent::__construct($name, $data, $dataName);
  }
   */

  /**
   * Tests
   */
  public function testConstructeurValide(): void {
    $instant1 = new Instant("2023-11-25 21:32", new DateTimeZone('Europe/Paris'));
    $instant2 = new Instant("2023-11-26 17:04", new DateTimeZone('Europe/Paris'));
    try {
      $this->intervalle = new Intervalle_Temporel($instant1, $instant2);
    } catch (Exception $e) {
    }
    $this->assertNotNull($this->intervalle);
  }
  
  /**
   * Tests
   */
  public function testConstructeurNonValide(): void {
    $instant1 = new Instant("2023-11-25 21:32", new DateTimeZone('Europe/Paris'));
    $instant2 = new Instant("2023-11-26 17:04", new DateTimeZone('Europe/Paris'));
    try {
      $this->intervalle = new Intervalle_Temporel($instant2, $instant1);
    } catch (Exception $e) {
    }
    $this->assertNull($this->intervalle);
  }
 
  /**
   * Tests
   */
  public function testChevauchementIntervallesBornes(): void {
    $t1 = new Instant("2023-11-25 21:32", new DateTimeZone('Europe/Paris'));
    $t2 = new Instant("2023-11-25 22:32", new DateTimeZone('Europe/Paris'));
    $t3 = new Instant("2023-11-26 17:04", new DateTimeZone('Europe/Paris'));
    $t4 = new Instant("2023-11-26 18:04", new DateTimeZone('Europe/Paris'));
    
    $i1 = new Intervalle_Temporel($t1, $t3);
    $i2 = new Intervalle_Temporel($t2, $t4);
    $this->assertTrue($i1->chevauche($i2), "[t1,t3] chevauche [t2,t4]");
    $this->assertTrue($i2->chevauche($i1), "[t2,t4] chevauche [t1,t3]");
    
    // Intervalles sans intersection
    $i3 = new Intervalle_Temporel($t1, $t2);
    $i4 = new Intervalle_temporel($t3, $t4);
    $this->assertFalse($i3->chevauche($i4), "[t1,t2] ne chevauche pas [t3,t4]");
    $this->assertFalse($i4->chevauche($i3), "[t3,t4] ne chevauche pas [t1,t2]");

    // Intervalle qui couvre un autre
    $i5 = new Intervalle_Temporel($t1, $t4);
    $i6 = new Intervalle_temporel($t2, $t3);
    $this->assertTrue($i5->chevauche($i6), "[t1,t4] chevauche [t2,t3]");
    $this->assertTrue($i6->chevauche($i5), "[t2,t3] chevauche [t1,t4]");
    
    // Intervalles identiques
    $i7 = new Intervalle_Temporel($t1, $t2);
    $this->assertTrue($i3->chevauche($i7), "[t1,t2] chevauche [t1,t2]");
  }

  /**
   * Tests
   */
  public function testCouvertureIntervallesBornes(): void {
    $t1 = new Instant("2023-11-25 21:32", new DateTimeZone('Europe/Paris'));
    $t2 = new Instant("2023-11-25 22:32", new DateTimeZone('Europe/Paris'));
    $t3 = new Instant("2023-11-26 17:04", new DateTimeZone('Europe/Paris'));
    $t4 = new Instant("2023-11-26 18:04", new DateTimeZone('Europe/Paris'));
    
    $i1 = new Intervalle_Temporel($t1, $t3);
    $i2 = new Intervalle_Temporel($t2, $t4);
    $this->assertFalse($i1->couvre($i2), "[t1,t3] ne couvre pas [t2,t4]");
    $this->assertFalse($i2->couvre($i1), "[t2,t4] ne couvre pas [t1,t3]");
    
    // Intervalles sans intersection
    $i3 = new Intervalle_Temporel($t1, $t2);
    $i4 = new Intervalle_temporel($t3, $t4);
    $this->assertFalse($i3->couvre($i4), "[t1,t2] ne couvre pas [t3,t4]");
    $this->assertFalse($i4->couvre($i3), "[t3,t4] ne couvre pas [t1,t2]");

    // Intervalle qui couvre un autre
    $i5 = new Intervalle_Temporel($t1, $t4);
    $i6 = new Intervalle_temporel($t2, $t3);
    $this->assertTrue($i5->couvre($i6), "[t1, t4] couvre [t2,t3]");
    $this->assertFalse($i6->couvre($i5), "[t2, t3] ne couvre pas [t1,t4]");
    
    $i7 = new Intervalle_Temporel($t1, $t2);
    $this->assertTrue($i3->chevauche($i7), "[t1,t2] couvre [t1,t2]");
  }
  
}
// ===========================================================================
?>
