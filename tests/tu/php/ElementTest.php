<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once './Element_Concret.php';

set_include_path('./../../..');

// Classe sous test
require_once 'php/elements_page/generiques/element.php';
// Dependances
require_once 'php/elements_page/generiques/page.php';

/**
 * Element etant une classe abstraite avec un implementation partielle,
 * definition d'une classe concrete pour tester les methodes definies
 */
/*
class Element_Concret extends Element {
  public function initialiser(): void {}
  protected function afficher_debut(): void { print("Debut"); }
  protected function afficher_corps(): void { print("Corps"); }
  protected function afficher_fin(): void { print("Fin"); }
}
*/
/**
 * test case.
 */
class ElementTest extends TestCase {
  /**
   * instance of the class under test
   * @var $page
   */
  private $element;

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp(): void {
    parent::setUp();
    $this->element = new Element_Concret();
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown(): void {
    $this->element = null;
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
   * Test definition du titre de l'element (qui n'est pas obligatoire)
   */
  public function testDefinitionTitre() {
    $this->assertFalse($this->element->a_un_titre());
    $valeur = "Un titre avec caractères non ASCII : être \nhélène";
    $this->element->def_titre($valeur); 
    $this->assertEquals($valeur, $this->element->titre());
    $this->assertTrue($this->element->a_un_titre());
  }

  /**
   * Test definition de l'Id html de l'element
   * information non obligatoire
   */
  public function testDefinitionId() {
    $valeur = "id_element_html";
    $this->element->def_id($valeur);
    $this->assertEquals($valeur, $this->element->id());
  }

  /**
   * Test definition de la page dans laquel l'element est place
   * information non obligatoire
   */
  public function testDefinitionPage() {
    $page = new Page_Simple("", "");
    $this->element->def_page($page);
    $this->assertNotNull($this->element->page());
  }

  /**
   * Test methode afficher (doit afficher l'entete, le corps et le pied
   * de l'elément
   */
  public function testAfficher() {
    $this->expectOutputString("DebutCorpsFin");
    $this->element->afficher();
  }

}

