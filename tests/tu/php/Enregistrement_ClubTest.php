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
 * description : Tests fonctionnels unitaires de la classe Enregitrement_Club
 * utilisation : phpunit --testdox <chemin_vers_ce_fichier_php>
 * dependances :
 * - 
 * utilise avec :
 * - PHP 8.2 et PHPUnit 9.5 sur macOS 13.6
 * - PHP 8.5 et PHPUnit 13.6 sur macOS 27
 * ----------------------------------------------------------------------------
 * creation : 14-fev-2023 pchevaillier@gmail.com
 * revision : 03-dec-2023 pchevaillier@gmail.com verification_identite
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
  
  /**
   *
   */

  public function testVerifierIdentiteBonMotPasse(): void {
    $mot_passe = 'motdepassebidon';
    print(bin2hex(password_hash($mot_passe, PASSWORD_BCRYPT)) . PHP_EOL);
    $code_club = 1;
    $club = new Club($code_club);
    $this->enregistrement->def_club($club);
    $this->enregistrement->lire();
    $ok = false;
    $ok = $this->enregistrement->verifier_identite($mot_passe);
    $this->assertTrue($ok);
  }

  public function testVerifierIdentiteMauvaisMotPasse(): void {
    $mot_passe = 'cestPasLeBon';
    $code_club = 1;
    $club = new Club($code_club);
    $this->enregistrement->def_club($club);
    $this->enregistrement->lire();
    
    $ok = false;
    try {
      $ok = $this->enregistrement->verifier_identite($mot_passe);
    } catch (Erreur_Mot_Passe_Club $e) {
      $this->assertFalse($ok);
    }
  }
  
  public function testVerifierIdentiteCodeClubErrone(): void {
    $mot_passe = '';
    $code_club = 12;
    $club = new Club($code_club);
    $this->enregistrement->def_club($club);
    $this->enregistrement->lire();
    
    $ok = false;
    try {
      $ok = $this->enregistrement->verifier_identite($mot_passe);
    } catch (Erreur_Club_Introuvable $e) {
      $this->assertFalse($ok);
    }
  }
  
  public function testVerifierIdentiteIdentifiantClubErrone(): void {
    $mot_passe = '';
    $code_club = 1;
    $club = new Club($code_club);
    $this->enregistrement->def_club($club);
    $this->enregistrement->lire();
    $club->def_identifiant('AutreCLub');
    
    $ok = false;
    try {
      $ok = $this->enregistrement->verifier_identite($mot_passe);
    } catch (Erreur_Identifiant_Club $e) {
      $this->assertFalse($ok);
    }
  }
}
// ==========================================================================
?>
