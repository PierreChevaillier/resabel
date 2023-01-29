<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

set_include_path('./../../..');

// Classe sous test
require_once 'php/elements_page/generiques/element.php';
// Dependances

/**
 * test case.
 */
class Element_CodeTest extends TestCase {
  /**
   * instance of the class under test
   * @var $page
   */
  private $element;
  private $code_html = "<p>Code html de l'élément</p>";

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp(): void {
    parent::setUp();
    $this->element = new Element_Code();
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown(): void {
    $this->element = null;
    parent::tearDown();
  }

  /**
   * Test definition du code html de l'element
   */
  public function testDefinitionCodeHtml() {
    $this->assertFalse($this->element->est_non_vide());
    $this->element->def_code($this->code_html);
    $this->assertEquals($this->code_html, $this->element->code());
    $this->assertTrue($this->element->est_non_vide());
  }


  /**
   * Test methode afficher
   */
  public function testAfficher() {
    $this->element->def_code($this->code_html);
    $this->expectOutputString($this->code_html);
    $this->element->afficher();
  }

}

