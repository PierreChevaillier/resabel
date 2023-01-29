<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once './Element_Concret.php';

set_include_path('./../../..');

// Classe sous test
require_once 'php/elements_page/generiques/element.php';

/**
 * test case.
 */
class Conteneur_ElementsTest extends TestCase {
  /**
   * instance of the class under test
   * @var $page
   */
  private ?Conteneur_Elements $conteneur;

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp(): void {
    parent::setUp();
    $this->conteneur = new Conteneur_Elements();
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown(): void {
    $this->conteneur = null;
    parent::tearDown();
  }

  public function testInitialiserConteneurAvecDeuxElements() {
    $e1 = new Element_Concret();
    $e1->id = "1";
    $e2 = new Element_Concret();
    $e2->id = "2";
    $this->conteneur->ajouter_element($e1);
    $this->conteneur->ajouter_element($e2);
    $this->conteneur->initialiser();
    $this->assertEquals("Debut1", $e1->debut);
    $this->assertEquals("Debut2", $e2->debut);
  }

  /**
   * Test
   */
  public function testAfficherConteneurVide() {
    $this->expectOutputString(PHP_EOL . "" . PHP_EOL);
    $this->conteneur->afficher();
  }

  public function testAfficherConteneurAvecUnElement() {
    $element = new Element_Concret();
    $str = "DebutCorpsFin";
    $this->conteneur->ajouter_element($element);
    $this->expectOutputString(PHP_EOL . $str . PHP_EOL);
    $this->conteneur->afficher();
  }

  public function testAfficherConteneurAvecDeuxElements() {
    $e1 = new Element_Concret();
    $e1->id = "1";
    $e1->initialiser();
    $str1 = "Debut1Corps1Fin1";
    $e2 = new Element_Concret();
    $e2->id = "2";
    $e2->initialiser();
    $str2 = "Debut2Corps2Fin2";
    $str = PHP_EOL . $str1 . $str2 . PHP_EOL;
    $this->conteneur->ajouter_element($e1);
    $this->conteneur->ajouter_element($e2);
    $this->expectOutputString($str);
    $this->conteneur->afficher();
  }

  public function testAfficherConteneurAvecUnConteneur() {
    $c = new Conteneur_Elements();
    $this->conteneur->ajouter_element($c);
 
    $e = new Element_Concret();
    $c->ajouter_element($e);
    $str_e = "DebutCorpsFin";
    
    $str_c = PHP_EOL . PHP_EOL . $str_e . PHP_EOL . PHP_EOL;
    $this->expectOutputString($str_c);
    $this->conteneur->afficher();
  }

 
}

