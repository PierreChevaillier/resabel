<?php
// ==========================================================================
// contexte : Resabel - systeme de REServAtion de Bateau En Ligne
//            Tests unitaires
// description : Test unitaire de la classe Instant
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
final class InstantTest extends TestCase {
  /**
   * instance of the class under test
   * @var $page
   */
  private $instant;

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp(): void {
    setlocale(LC_ALL, 'fr_FR.utf-8', 'french');
    // jour > 12 et minutes > 23
    $this->instant = new Instant("2023-11-25 21:32",
                                 new DateTimeZone('Europe/Paris'));
    parent::setUp();
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown(): void {
    $this->instant= null;
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
  public function testValeurCle(): void {
    $cle = $this->instant->valeur_cle();
    $this->assertEquals($cle, "2023-11-25 21:32");
    
    $instant2 = new Instant("2023-02-01 03:04");
    $cle = $instant2->valeur_cle();
    $this->assertEquals($cle, "2023-02-01 03:04");

  }
  
  /**
   * Tests
   */
  public function testDateEnTexteFormatLongFrancais(): void {
    $str = $this->instant->date_texte();
    $this->assertEquals($str, "Samedi 25 novembre 2023", "mauvais format date texte : " . $str);
  }
  
  /**
   * Tests
   */
  public function testDateEnTexteFormatAbbregeFrancais(): void {
    $str = $this->instant->date_texte_abbr();
    $this->assertEquals($str, "Sam 25 nov 2023", "mauvais format date texte abbrege : " . $str);
  }

  /**
   * Tests
   */
  public function testDateEnTexteForamtCourtFrancais(): void {
    $str = $this->instant->date_texte_court();
    $this->assertEquals($str, "Sam 25 nov", "mauvais format date texte court : " . $str);
  }

  /**
   * Tests
   */
  public function testEstAvantUnAutre(): void {
    $instant1 = new Instant("2023-11-25 21:32", new DateTimeZone('Europe/Paris'));
    $instant2 = new Instant("2023-11-26 17:04", new DateTimeZone('Europe/Paris'));
    $this->assertTrue($instant1->est_avant($instant2));
    $this->assertFalse($instant2->est_avant($instant1));
  }

  public function testEstAvantLuiMeme(): void {
    $instant1 = new Instant("2023-11-25 21:32", new DateTimeZone('Europe/Paris'));
    $this->assertTrue($instant1->est_avant($instant1));
  }
  
  public function testEstApresUnAutre(): void {
    $instant1 = new Instant("2023-11-25 21:32", new DateTimeZone('Europe/Paris'));
    $instant2 = new Instant("2023-11-26 17:04", new DateTimeZone('Europe/Paris'));
    $this->assertTrue($instant2->est_apres($instant1));
    $this->assertFalse($instant1->est_apres($instant2));
  }

  public function testEstApresLuiMeme(): void {
    $instant1 = new Instant("2023-11-25 21:32", new DateTimeZone('Europe/Paris'));
    $this->assertTrue($instant1->est_avant($instant1));
  }

}
// ===========================================================================
?>
