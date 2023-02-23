<?php
// ==========================================================================
// contexte : Resabel - systeme de REServAtion de Bateau En Ligne
//            Tests unitaires
// description : Test unitaire de la classe Activite_Journaliere
// copyright (c) 2023 AMP. Tous droits reserves.
// --------------------------------------------------------------------------
// utilisation : phpunit --testdox <chemin_vers_ce_fichier_php>
// dependances :
// utilise avec :
//  - depuis 2023 :
//    PHP 8.2 et PHPUnit 9.5 sur macOS 13.2 ;
// --------------------------------------------------------------------------
// creation : 18-fev-2023 pchevaillier@gmail.com
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

// --------------------------------------------------------------------------
// --- Classes de l'enviromment de test
include_once './base_donnees.php';

// --- Classes de l'application
set_include_path('./../../../');

require_once('php/metier/activite.php');

// ==========================================================================
/**
 * Test case.
 */
class Activite_JournaliereTest extends TestCase {

  private $activite_journaliere;

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp(): void {
    parent::setUp();
    $_SESSION['clb'] = 1;
    $this->activite_journaliere = new Activite_Journaliere();
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown(): void {
    parent::tearDown();
  }

  /**
   * 
   */
  public function testCollecterInformations(): void {
    $jour = Calendrier::aujourdhui();
    $this->activite_journaliere->def_date_jour($jour);
    $this->activite_journaliere->debut_plage_horaire = $jour->add(new DateInterval('PT0H'));
    $this->activite_journaliere->fin_plage_horaire = $jour->add(new DateInterval('PT23H59M'));
    
    $status = $this->activite_journaliere->collecter_informations();
    
    $this->assertTrue($status, "recuperation des informations sur l'activite journaliere");
    $this->assertNotNull($this->activite_journaliere->club, "club renseigne, donc non null");
    $this->assertTrue(count($this->activite_journaliere->personnes_actives) > 0);
    $condition = $this->activite_journaliere->nombre_sites() > 0;
    $this->assertTrue($condition);
    $this->assertEquals($this->activite_journaliere->nombre_activite_sites(), $this->activite_journaliere->nombre_sites());
  }
  
}
// ==========================================================================
?>
