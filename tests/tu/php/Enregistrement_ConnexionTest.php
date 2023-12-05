<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 *            Test unitaire
 * description :  Test unitaire de la classe Enregistrement_Connexion
 * copyright (c) 2014-2023 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : phpunit
 * dependances :
 * - classe de Resabel sous test : Enregistrement_Connexion
 * - enregistrement present dans la table de la base de donnees de test
 * utilise avec :
 * - depuis 2023 :
 *   PHP 8.2 sur macOS 13.x et PHPUnit 9.5
 * ----------------------------------------------------------------------------
 * creation : 16-nov-2023 pchevaillier@gmail.com
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
// --- Classes de l'environnement de test

// --- Acces a la base de donnees dediee au test
include_once './base_donnees.php';

// --- Classes de l'application
set_include_path('./../../../');

include_once('php/utilitaires/definir_locale.php');
require_once('php/metier/calendrier.php');
require_once('php/metier/connexion.php');

// --- Classe sous test
require_once('php/bdd/enregistrement_connexion.php');

// ============================================================================
/**
 * Test case.
 */
final class Enregistrement_ConnexionTest extends TestCase {
  
  private static ?PDO $bdd = null;
  
  private static $donnees = null;
  private static $donneesTest = null;
  

  public static function setUpBeforeClass(): void {
    print("Connection to the database" . PHP_EOL);
    self::$bdd = Base_Donnees::acces();
    self::creer_donnees();
    self::generer_donnees_test();
  }

  public static function tearDownAfterClass(): void {
    print("Delete connection handler" . PHP_EOL);
    self::$bdd = null;
  }
  
  private static function creer_donnees(): void {
    // donnees supposees se trouver dans la table Connexion utilisee pour ce test
    self::$donnees = array (
                            array (101, "pierre.chevaillier", "motdepassebidon", 0, 1),
                            array (60, "joel.champeau", "motdepassebidon", 1, 1),
                            array (9004, "jorge", "motdepassebidon", 0, 0)
    );
  }
  
  private static function generer_donnees_test(): void {
    foreach (self::$donnees as $donnee) {
      $cnx = new Connexion();
      self::$donneesTest[] = array($donnee, $cnx);
      print($donnee[1]);
      $cnx->def_code_membre($donnee[0]);
      $cnx->def_identifiant($donnee[1]);
      $mdp = password_hash($donnee[2], PASSWORD_BCRYPT);
      $cnx->def_mot_de_passe($mdp);
    }
    //print_r(self::$donneesTest);
  }
  
  
  public function testVerificationIdentite(): void {
    $enreg = new Enregistrement_Connexion();
    // Personne referencee avec combinaison (actif, autorise)
    foreach (self::$donneesTest as $donnee) {
      $cnx = $donnee[1];
      $enreg->def_connexion($cnx);
      $resultat = null; // independance des tests
      $mdp_clair = $donnee[0][2];
      
      $resultat = $enreg->verifier_identite($mdp_clair); // appel methode sous test
      
      $this->assertTrue($resultat);
      $this->assertEquals($donnee[0][0], $cnx->code_membre());
      
      $code_actif = $donnee[0][3];
      $est_actif = ($code_actif == 1);
      $this->assertEquals($est_actif, $cnx->est_compte_actif());
      
      $code_autorise = $donnee[0][4];
      $est_autorise = ($code_autorise == 1);
      $this->assertEquals($est_autorise, $cnx->est_autorise());
      
      $date = $cnx->date_derniere_connexion();
      if (is_null($date))
        print(PHP_EOL . "Date derniere connexion de "
              . $cnx->identifiant()
              . " non definie");
      else
        print(PHP_EOL . "Date derniere connexion de "
              . $cnx->identifiant()
              . " : " . $date->date_heure_sql() . PHP_EOL);
    }
    
    // Personne non referencee
    $cnx = new Connexion();
    $cnx->def_identifiant("inconnue");
    $enreg->def_connexion($cnx);
    $resultat = null;
    try {
      $resultat = $enreg->verifier_identite("nimporte_quoi");
    } catch (Exception $e) {
      $this->assertInstanceOf(Erreur_Identifiant_Connexion::class, $e);
      $this->assertNull($resultat);
    }
    
    // Personne non referencee mais mauvais mot de passe
    $cnx = new Connexion();
    $cnx->def_identifiant("pierre.chevaillier");
    $cnx->def_mot_de_passe("mauvais mot de passe");
    $enreg->def_connexion($cnx);
    $resultat = null;
    try {
      $resultat = $enreg->verifier_identite("nimporte_quoi");
    } catch (Exception $e) {
      $this->assertInstanceOf(Erreur_Mot_Passe_Connexion::class, $e);
      $this->assertNull($resultat);
    }
  }
  
  public function testModifierDateDerniereConnexion(): void {
    $enreg = new Enregistrement_Connexion();
    $cnx = new Connexion();
    $enreg->def_connexion($cnx);
    
    $cnx->def_date_derniere_connexion(new Instant());
    $cnx->def_code_membre(self::$donnees[0][0]);
    
    $enreg->modifier_date_derniere_connexion();
    
  }
  
  public function testModifierMotDePasse(): void {
    $enreg = new Enregistrement_Connexion();
    $cnx = new Connexion();
    
    $status = $enreg->modifier_mot_de_passe();// appel methode sous test
    $this->assertFalse($status);
    
    $enreg->def_connexion($cnx);
    $cnx->def_code_membre(self::$donnees[0][0]);
    $mdp_clair = self::$donnees[0][2];
    $mdp = password_hash($mdp_clair, PASSWORD_BCRYPT);
    $cnx->def_mot_de_passe($mdp);

    $status = $enreg->modifier_mot_de_passe();
    $this->assertTrue($status);

  }
  
  public function testVerifierIdentifiantUnique(): void {
    $enreg = new Enregistrement_Connexion();
    
    // Cas de l'identifiant correspondant au code membre present dans la table
    $cnx = self::$donneesTest[0][1];
    $enreg->def_connexion($cnx);
    $identifiant = $cnx->identifiant();
    print(PHP_EOL . "code :" . $cnx->code_membre() .  " id : " . $cnx->identifiant() . PHP_EOL);
    $resultat = $enreg->verifier_identifiant_unique($identifiant);
    $this->assertTrue($resultat);
    
    // Cas d'un identifiant ne correspondant pas a un code_membre present dans la table
    $identifiant = $cnx->identifiant() . "plus quelque chose d'improbable";
    $resultat = $enreg->verifier_identifiant_unique($identifiant);
    $this->assertTrue($resultat);
 
    // Cas d'un identifiant existant et d'un code_membre existant dans la table
    // mais l'un pas associe a l'autre
    // cas qui ne devrait pas se produire...
    $code = self::$donnees[1][0];
    $cnx->def_code_membre($code);
    $identifiant = $cnx->identifiant();
    $resultat = $enreg->verifier_identifiant_unique($identifiant);
    $msg = "code :" . $cnx->code_membre() .  " id : " . $identifiant;
    print(PHP_EOL . $msg);
    $this->assertFalse($resultat, $msg);

    // Cas d'un identifiant existant et d'un code membre absent de la table
    // cas qui ne devrait pas se produire...
    $code = $cnx->code_membre() + 1000000;
    $cnx->def_code_membre($code);
    $identifiant = $cnx->identifiant();
    $resultat = $enreg->verifier_identifiant_unique($identifiant);
    $msg = "code :" . $cnx->code_membre() .  " id : " . $identifiant;
    print(PHP_EOL . $msg);
    $this->assertFalse($resultat, $msg);
    
  }
}
// ============================================================================
?>
