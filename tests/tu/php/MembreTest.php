<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 *            Test unitaire
 * description :  Test unitaire de la classe metier Membre
 * copyright (c) 2014-2023 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : phpunit
 * dependances :
 * - classe de Resabel sous test : Membre
 * utilise avec :
 * - depuis 2023 :
 *   PHP 8.2 sur macOS 13.x et PHPUnit 9.5
 * ----------------------------------------------------------------------------
 * creation : 17-nov-2023 pchevaillier@gmail.com
 * revision :
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * - incomplet : manque le test des instants 
 * a faire :
 * -
 * ============================================================================
*/
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

// ----------------------------------------------------------------------------
// --- Classes de l'environement de test

// --- Classes de l'application
set_include_path('./../../../');

// --- Classe sous test
require_once('php/metier/membre.php');

// ============================================================================
/**
 * Test case.
 */
final class MembreTest extends TestCase {
  
  public function testDefinitionEvaluationChefDeBord(): void {
    $mbr = new Membre(1);
    $this->assertFalse($mbr->est_chef_de_bord());
    $mbr->def_est_chef_de_bord(false);
    $this->assertFalse($mbr->est_chef_de_bord());
    
    $cdb = new Membre(1);
    $cdb->def_est_chef_de_bord(true);
    $this->assertTrue($cdb->est_chef_de_bord());
    $cdb->def_chef_de_bord(1);
    $this->assertTrue($cdb->est_chef_de_bord());
    
  }
  
  public function testDefinitionEvaluationMembreActif(): void {
    $mbr = new Membre(1);
    $this->assertFalse($mbr->est_actif());
    $mbr->def_actif(0);
    $this->assertFalse($mbr->est_actif());

    $mbr->def_actif(1);
    $this->assertTrue($mbr->est_actif());
  }
  
  public function testEvaluationAutorisationConnexion(): void {
    $mbr = new Membre(1);
    $this->assertFalse($mbr->est_autorise_connecter());
    
    $cnx = new Connexion();
    $mbr->def_connexion($cnx);
    $cnx->def_est_autorise(1);
    $this->assertTrue($mbr->est_autorise_connecter());
    $cnx->def_est_autorise(0);
    $this->assertFalse($mbr->est_autorise_connecter());
  }

  public function testInitialiserDebutant(): void {
    $mbr = new Membre(1);
    $mbr->initialiser_debutant();
    
    $this->assertTrue($mbr->est_actif());
    $this->assertTrue($mbr->est_autorise_connecter());
    $this->assertFalse($mbr->est_chef_de_bord());
    
    $this->assertTrue($mbr->est_debutant());
  }

  public function testInitialiserVisiteur(): void {
    $mbr = new Membre(1);
    $mbr->initialiser_visiteur();
    
    $this->assertTrue($mbr->est_actif());
    $this->assertFalse($mbr->est_autorise_connecter());
    $this->assertFalse($mbr->est_chef_de_bord());
    $this->assertTrue($mbr->est_debutant());
  }
}
// ============================================================================
?>
