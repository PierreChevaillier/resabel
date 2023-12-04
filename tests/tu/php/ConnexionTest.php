<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 *            Test unitaire
 * description :  Test unitaire de la classe metier Connexion
 * copyright (c) 2014-2023 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : phpunit
 * dependances :
 * - classe de Resabel sous test : Connexion
 * utilise avec :
 * - depuis 2023 :
 *   PHP 8.2 sur macOS 13.x et PHPUnit 9.5
 * ----------------------------------------------------------------------------
 * creation : 13-nov-2023 pchevaillier@gmail.com
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
require_once('php/metier/connexion.php');

// ============================================================================
/**
 * Test case.
 */
final class ConnexionTest extends TestCase {
  
  private static $donnees = null;
  private static $donneesTest = null;
  

  public static function setUpBeforeClass(): void {
    self::creer_donnees();
    self::generer_donnees_test();
  }

  private static function creer_donnees(): void {
    self::$donnees = array (
                            1 => array (23005, "annie", "toto", 0, 0),
                            2 => array (24000, "barnabe", "", 0, 1),
                            3 => array (9005, "clemence", "un très très long mot de passe...", 1, 0),
                            5 => array (1, "duduche", "lAMP7o13Hir!", 1, 1)
    );
  }
  
  private static function generer_donnees_test(): void {
    foreach (self::$donnees as $donnee) {
      $cnx = new Connexion();
      self::$donneesTest[] = array($donnee, $cnx);
    }
    //print_r(self::$donneesTest);
  }
  
  public function testDefinitionEvaluationCodeMembre(): void {
    $code = -1;
    foreach (self::$donneesTest as $donnee) {
      $cnx = $donnee[1];
      $code = $donnee[0][0];
      $cnx->def_code_membre($code);
      $this->assertEquals($code, $cnx->code_membre());
      }
  }
  
  public function testDefinitionEvaluationIdentifiantConnexion(): void {
    $identifiant = "";
    foreach (self::$donneesTest as $donnee) {
      $cnx = $donnee[1];
      $identifiant = $donnee[0][1];
      $cnx->def_identifiant($identifiant);
      $this->assertEquals($identifiant, $cnx->identifiant());
      }
  }

  public function testDefinitionEvaluationMotDePasse(): void {
    $mdp = "";
    foreach (self::$donneesTest as $donnee) {
      $cnx = $donnee[1];
      $mdp = $donnee[0][2];
      $cnx->def_mot_de_passe($mdp); // juste pour le test car normalement c'est la valeur cryptee
      $this->assertEquals($mdp, $cnx->mot_de_passe());
      }
  }

  public function testDefinitionEvaluationEstCompteActif(): void {
    $code_actif = -1;
    $est_actif = false;
    foreach (self::$donneesTest as $donnee) {
      $cnx = $donnee[1];
      $code_actif = $donnee[0][3];
      $est_actif = ($code_actif == 1);
      $cnx->def_est_compte_actif($code_actif);
      $this->assertEquals($est_actif, $cnx->est_compte_actif());
      }
  }
  
  public function testDefinitionEvaluationAutorisationConnexion(): void {
    $code_autorise = -1;
    $est_autorise = false;
    foreach (self::$donneesTest as $donnee) {
      $cnx = $donnee[1];
      $code_autorise = $donnee[0][4];
      $est_autorise = ($code_autorise == 1);
      $cnx->def_est_autorise($code_autorise);
      $this->assertEquals($est_autorise, $cnx->est_autorise());
      }
  }
  
  public function testDefinitionEvaluationDateDerniereConnexion(): void {
    $date = Calendrier::maintenant();
    $timestamp = $date->getTimestamp();
    
    $cnx = new Connexion();
    $cnx->def_date_derniere_connexion($date);

    $this->assertEquals($timestamp, $cnx->date_derniere_connexion()->getTimestamp());
  }
  
  public function testVerificationMotDePasse(): void {
    foreach (self::$donneesTest as $donnee) {
      $cnx = $donnee[1];
      $mdp_clair = $donnee[0][2];
      $mdp_crypte = password_hash($mdp_clair, PASSWORD_BCRYPT);
      $cnx->def_mot_de_passe($mdp_crypte);
      $message = "Echec verification mot de passe : " .  $mdp_clair ;
      $this->assertTrue($cnx->verifier_mot_passe($mdp_clair), $message);
    }
  }
}
// ============================================================================
?>
