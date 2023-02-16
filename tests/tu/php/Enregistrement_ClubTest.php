<?php
// ==========================================================================
// contexte : Resabel - systeme de REServAtion de Bateau En Ligne
//            Tests unitaires
// description : Test unitaire de la classe Enregitrement_Club
// copyright (c) 2023 AMP. Tous droits reserves.
// --------------------------------------------------------------------------
// utilisation : phpunit --testdox <chemin_vers_ce_fichier_php>
// dependances :
// utilise avec :
//  - depuis 2023 :
//    PHP 8.2 et PHPUnit 9.5 sur macOS 13.2 ;
// --------------------------------------------------------------------------
// creation : 04-fev-2023 pchevaillier@gmail.com
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

require_once('php/bdd/enregistrement_club.php');

// ==========================================================================
/**
 * Test case.
 */
class Enregistrement_ClubTest extends TestCase {

  private $enregistrement;

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp(): void {
    parent::setUp();
    $this->enregistrement = new Enregistrement_Club();
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown(): void {
    parent::tearDown();
  }

  /**
   * Teste le nom de la table spource des informations
   */
  public function testSourceTableClub(): void {
    $this->assertEquals(PREFIX_TABLE . 'club', Enregistrement_Club::source());
  }
  
  public function testClubExiste(): void {
    $identifiant_club = 'AMP';
    $this->assertTrue(Enregistrement_Club::tester_existe($identifiant_club));
    $identifiant_club = 'XXX';
    $this->assertFalse(Enregistrement_Club::tester_existe($identifiant_club));
  }

  public function testLireInformationClub(): void {
    $code_club = 1;
    $club = new Club($code_club);
    $this->enregistrement->def_club($club);
    $this->assertTrue($this->enregistrement->lire());
    $this->assertEquals($club->identifiant(), 'AMP');
    $this->assertEquals($club->fuseau_horaire()->getName(), 'Europe/Paris');
  }
  
  public function testVerifierIdentite(): void {
    $id_club = 'AMP';
    $mot_passe = 'cestPasLeBon';
    $code_club = 1;
    $club = new Club($code_club);
    $this->enregistrement->def_club($club);
    $ok = false;
    try {
      $ok = $this->enregistrement->verifier_identite($id_club, $mot_passe);
      $this->assertFalse($ok);
    } catch (Erreur_Mot_Passe_Club $e) {
      $this->assertFalse($ok);
    }
    
  }

}
// ==========================================================================
?>
