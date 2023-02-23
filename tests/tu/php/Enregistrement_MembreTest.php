<?php
// ==========================================================================
// contexte : Resabel - systeme de REServAtion de Bateau En Ligne
//            Tests unitaires
// description : Test unitaire de la classe Enregitrement_Membre
// copyright (c) 2023 AMP. Tous droits reserves.
// --------------------------------------------------------------------------
// utilisation : phpunit --testdox <chemin_vers_ce_fichier_php>
// dependances :
// utilise avec :
//  - depuis 2023 :
//    PHP 8.2 et PHPUnit 9.5 sur macOS 13.2 ;
// --------------------------------------------------------------------------
// creation : 20-fev-2023 pchevaillier@gmail.com
// revision :
// --------------------------------------------------------------------------
// commentaires :
// - debut
// attention :
//  - incomplet
//  - donnees de test lues dans la base de donnees
// a faire :
// - beaucoup de choses
// ==========================================================================
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

// --------------------------------------------------------------------------
// --- Classes de l'enviromment de test
include_once './base_donnees.php';

// --- Classes de l'application
set_include_path('./../../../');

require_once('php/bdd/enregistrement_membre.php');

// ==========================================================================
/**
 * Test case.
 */
class Enregistrement_MembreTest extends TestCase {

  private int $code_composante_resabel = 2;
  private int $code_role_admin = 8;
  
  private int $code_admin_resabel = 101;
  private int $code_cdb = 20;
  private int $code_equipier = 19007;
  private int $code_visiteur = 9021;
  
  private $personae = array();
  
  private Enregistrement_Membre $enreg;
  
  private function collecter_persona(int $code): int {
    $status = 0;
    $persona = new Membre($code);
    $this->enreg->def_membre($persona);
    $trouve = $this->enreg->lire();
    if ($trouve) $this->personae[$code] = $persona;
    else $status = $code;
    return $status;
  }
  
  private function collecter_personae(): int {
    $status = 0;
    $status = $this->collecter_persona($this->code_admin_resabel);
    if ($status > 0) return $status;
    $status = $this->collecter_persona($this->code_cdb);
    if ($status > 0) return $status;
    $status = $this->collecter_persona($this->code_equipier);
    if ($status > 0) return $status;
    $status = $this->collecter_persona($this->code_visiteur);
    if ($status > 0) return $status;
    return $status;
  }
  
  /**
   * Prepares the environment before running a test.
   */
  protected function setUp(): void {
    parent::setUp();
    $this->enreg = new Enregistrement_Membre();
    $status = $this->collecter_personae();
    if ($status > 0) {
      print(PHP_EOL . "Erreur dans données de test - code membre : " . $status . PHP_EOL);
      die();
    }
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown(): void {
    parent::tearDown();
  }

  /**
   * Teste le nom de la table source des informations
   */
  public function testSourceTableClub(): void {
    $this->assertEquals(PREFIX_TABLE . 'membres', Enregistrement_Membre::source());
  }

  /**
   * Teste si une personne a le role administrateurice pour resabel
   */
  public function testRechercheSiPersonneEstAdminResabel() {
    $this->enreg->def_membre($this->personae[$this->code_admin_resabel]);
    $condition = $this->enreg->recherche_si_admin();
    $this->assertTrue($condition);
    $this->enreg->def_membre($this->personae[$this->code_equipier]);
    $condition = $this->enreg->recherche_si_admin();
    $this->assertFalse($condition);
  }
  
  public function testIdentifiantUnique() {
    
    // cas d'un identifiant associe (uniquement) a la bonne personne
    $code = 101;
    $identifiant ="pierre.chevaillier";
    $personne = $this->personae[$code];
    $this->enreg->def_membre($personne);
    $condition = $this->enreg->verifier_identifiant_unique($identifiant);
    $this->assertTrue($condition);
    
    // cas d'un identifiant associe a une autre personne, donc deja utilise
    $identifiant ="joel.champeau";
    $condition = $this->enreg->verifier_identifiant_unique($identifiant);
    $this->assertFalse($condition);
    
    // cas d'un identifiant non utilise
    $identifiant ="Duduche_azertyiop";
    $condition = $this->enreg->verifier_identifiant_unique($identifiant);
    $this->assertTrue($condition);
  }
  
}
// ==========================================================================
?>
