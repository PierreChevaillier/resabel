<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

set_include_path('./../../..');

require_once 'php/elements_page/generiques/page.php';
//require_once 'PHPUnit/Autoload.php';

/**
 * Page test case.
 */
class PageTest extends TestCase {
  /**
   * instance of the class under test
   * @var $page
   */
  private $page;

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp(): void {
    parent::setUp();
    //$this->page = new Page_Simple(/* parameters */);
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown(): void {
    $this->page = null;
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
   * Tests Page_Simple->__construct()
   */
  public function test__construct() {
    $nom_site = "PCh";
    $nom_page = "simple vide";
    $this->page = new Page_Simple($nom_site,
                                  $nom_page,
                                  $feuilles_style = null);
    
    // Titre de la page
    $titre = $this->page->titre();
    $message = "le titre de la page est '"
      . $titre .  "', il devrait contenir '"
      . $nom_site . "'";
    $this->assertStringContainsStringIgnoringCase($nom_site,
                                                 $titre,
                                                 $message);
    $this->assertStringContainsStringIgnoringCase($nom_page,
                                                 $titre,
                                                 $message);
    // Feuille style
    //$n = $this->page->feuilles_style->count();
  }

  /**
   * Tests Page->ajouter_script()
   */
  public function test_ajouter_script(): void {
    //$this->markTestIncomplete("doIt test not implemented");
    $this->page = new Page_Simple("", "");
    $this->assertCount(0, $this->page->javascripts,
                       "should not contain any script");
    $empty_path = "";
    $this->page->ajouter_script($empty_path);
    $this->assertCount(0, $this->page->javascripts,
                       "should not contain empty path");
    $path1 = "path_to_script_1";
    $this->page->ajouter_script($path1);
    $this->assertContains($path1, $this->page->javascripts);
    $this->page->ajouter_script($path1);
    $this->assertContains($path1, $this->page->javascripts);
    $this->assertCount(1, $this->page->javascripts);
    $path2 = "path_to_script_2";
    $this->page->ajouter_script($path2);
    $this->assertContains($path2, $this->page->javascripts);
    $this->assertCount(2, $this->page->javascripts);

  }
  /**
   * Tests HelloWorld->__destruct()
   */
  /*
  public function test__destruct()
  {
      // TODO Auto-generated HelloWorldTest->test__destruct()
      //$this->markTestIncomplete("__destruct test not implemented");

      $this->helloWorld->__destruct();
  }
  */
}

