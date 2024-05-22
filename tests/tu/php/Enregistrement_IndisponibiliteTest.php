<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 *            Test unitaire
 * description :  Test unitaire de la classe Enregistrement_Indisponibilite
 * copyright (c) 2014-2024 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : phpunit
 * dependances :
 * - classe de Resabel sous test : Enregistrement_Indisponibilite
 * - enregistrement present dans la table de la base de donnees de test
 * utilise avec :
 * - depuis 2023 :
 *   PHP 8.2 sur macOS 13.x et PHPUnit 9.5
 * ----------------------------------------------------------------------------
 * creation : 28-fev-2024 pchevaillier@gmail.com
 * revision : 15-may-2024 pchevaillier@gmail.com + testLireFermetureSite
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

// ----------------------------------------------------------------------------
// --- Classes de l'environnement de test

// --- Acces a la base de donnees dediee au test
include_once './base_donnees.php';

// --- Classes de l'application
set_include_path('./../../../');

include_once('php/utilitaires/definir_locale.php');
require_once('php/metier/calendrier.php');

// --- Classe sous test
require_once('php/bdd/enregistrement_indisponibilite.php');

// ============================================================================
/**
 * Test case.
 */
final class Enregistrement_IndisponibiliteTest extends TestCase {
  
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
    /*
    foreach (self::$donnees as $donnee) {
      $cnx = new Connexion();
      self::$donneesTest[] = array($donnee, $cnx);
      print($donnee[1]);
      $cnx->def_code_membre($donnee[0]);
      $cnx->def_identifiant($donnee[1]);
      $mdp = password_hash($donnee[2], PASSWORD_BCRYPT);
      $cnx->def_mot_de_passe($mdp);
    }
     */
    //print_r(self::$donneesTest);
  }
  
  
  public function testRequeteCollecterIndisponibiliteSupport(): void {
 
    //$cnx = self::$donneesTest[0][1];
    $code_classe_indisponibilite = Enregistrement_Indisponibilite::CODE_TYPE_INDISPO_SUPPORT;
    
    $critere_selection = "";
    $critere_tri = "";
    $requete_sql = Enregistrement_Indisponibilite::sql_requete_collecter($code_classe_indisponibilite,
                                                                         $critere_selection,
                                                                         $critere_tri
                                                                         );
    
    $msg = "Type indisponibilite :" . $code_classe_indisponibilite
      . " requete = " . PHP_EOL
      . $requete_sql;
    print(PHP_EOL . $msg . PHP_EOL);
    
    $indispos = array();
    $ok = Enregistrement_Indisponibilite::collecter(null,
                                                    $code_classe_indisponibilite,
                                                    $critere_selection,
                                                    $critere_tri,
                                                    $indispos
                                                    );
    $this->assertTrue($ok, $msg);
    
  }
  
  public function testRequeteCollecterFermetureSite(): void {
    $code_classe_indisponibilite = Enregistrement_Indisponibilite::CODE_TYPE_INDISPO_SITE;
    
    $critere_selection = "";
    $critere_tri = "";
    $requete_sql = Enregistrement_Indisponibilite::sql_requete_collecter($code_classe_indisponibilite,
                                                                         $critere_selection,
                                                                         $critere_tri
                                                                         );
    
    $msg = "Type indisponibilite :" . $code_classe_indisponibilite
      . " requete = " . PHP_EOL
      . $requete_sql;
    print(PHP_EOL . $msg . PHP_EOL);
    
    $indispos = array();
    $ok = Enregistrement_Indisponibilite::collecter(null,
                                                    $code_classe_indisponibilite,
                                                    $critere_selection,
                                                    $critere_tri,
                                                    $indispos
                                                    );
    $this->assertTrue($ok, $msg);
  }
  
  public function testLireFermetureSite(): void {
    $code = $this->code_dernier_enregistrement_fermeture();
    if ($code > 0) {
      $indispo = new Fermeture_Site($code);
      $enreg = new Enregistrement_Indisponibilite();
      $enreg->def_indisponibilite($indispo);
      
      $ok = $enreg->lire();
      $msg = PHP_EOL . "Lecture femeture site code :" . $code . PHP_EOL;
      $this->assertTrue($ok, $msg);
    }
  }
  
  /*
  public function testRequeteAjouterFermetureSite(): void {
    
    $enregistrement = new Enregistrement_Indisponibilite();
    $ok = $enregistrement->ajouter_fermeture_site();
    $this->assertFalse($ok);
    
    $code = 0;
    $fermeture = new Fermeture_Site($code);
    $enregistrement->def_indisponibilite($fermeture);
    
    $code_site = 2;
    $site = $this->getMockBuilder('Site_Activite')
      ->disableOriginalConstructor()
      ->getMockForAbstractClass();
    $site->__construct($code_site);
    $fermeture->def_site_activite($site);
    
    $motif = new Motif_Indisponibilite(1);
    $fermeture->def_motif($motif);
    $debut = new Instant("2024-04-20 23:56:00");
    $fin = $debut->add(new DateInterval('PT7H0M0S'));
    $fermeture->definir_periode($debut, $fin);
    
    $ok = $enregistrement->ajouter_fermeture_site();
    $this->assertTrue($ok);
    
  }
  */
  
  public function code_dernier_enregistrement(): int {
    $code = 0;
    $resultat = self::$bdd->query('SELECT MAX(code) AS n FROM ' . Enregistrement_Indisponibilite::source());
    while ($donnee = $resultat->fetch(PDO::FETCH_OBJ)) {
      $code = $donnee->n;
      break;
    }
    $resultat->closeCursor();
    return $code;
  }
  
  public function code_dernier_enregistrement_fermeture(): int {
    $code = 0;
    $sql_query = 'SELECT MAX(code) AS n FROM '
      . Enregistrement_Indisponibilite::source()
      . ' WHERE code_type = ' . Enregistrement_Indisponibilite::CODE_TYPE_INDISPO_SITE
      ;
    $resultat = self::$bdd->query($sql_query);
    while ($donnee = $resultat->fetch(PDO::FETCH_OBJ)) {
      $code = $donnee->n;
      break;
    }
    $resultat->closeCursor();
    return $code;
  }
  
  public function code_dernier_enregistrement_indisponibilite_support(): int {
    $code = 0;
    $sql_query = 'SELECT MAX(code) AS n FROM '
      . Enregistrement_Indisponibilite::source()
      . ' WHERE code_type = ' . Enregistrement_Indisponibilite::CODE_TYPE_INDISPO_SUPPORT
      ;
    $resultat = self::$bdd->query($sql_query);
    while ($donnee = $resultat->fetch(PDO::FETCH_OBJ)) {
      $code = $donnee->n;
      break;
    }
    $resultat->closeCursor();
    return $code;
  }
  
  /*
  public function testRequeteSupprimerIndisponibilite(): void {
    
    $enregistrement = new Enregistrement_Indisponibilite();
    $ok = $enregistrement->supprimer();
    $this->assertFalse($ok);
    
    $code_inexistant = 11;
    $indispo = $this->getMockBuilder('Indisponibilite')
      ->disableOriginalConstructor()
      ->getMockForAbstractClass();
    $indispo->__construct($code_inexistant);
    $enregistrement->def_indisponibilite($indispo);
    $ok = $enregistrement->supprimer();
    $this->assertFalse($ok);
    
    $code_existant = $this->code_dernier_enregistrement();
    $indispo = $this->getMockBuilder('Indisponibilite')
      ->disableOriginalConstructor()
      ->getMockForAbstractClass();
    $indispo->__construct($code_existant);
    $enregistrement->def_indisponibilite($indispo);
    $ok = $enregistrement->supprimer();
    $this->assertTrue($ok);
    
  }
  */
  public function testModifierEnregistrementIndisponibilite(): void {
    $enregistrement = new Enregistrement_Indisponibilite();
    $ok = $enregistrement->modifier();
    $this->assertFalse($ok);
    
    $code_existant = $this->code_dernier_enregistrement_indisponibilite_support();
    if ($code_existant > 0) {
      $indispo = new Indisponibilite_support($code_existant);
      $enregistrement->def_indisponibilite($indispo);
      
      $motif = new Motif_Indisponibilite(1);
      $indispo->def_motif($motif);
      $debut = new Instant("2024-04-25 21:48:00");
      $fin = $debut->add(new DateInterval('PT8H0M0S'));
      $indispo->definir_periode($debut, $fin);
      
      $support = new Support_Activite(1);
      $indispo ->def_support($support);
      
      $ok = $enregistrement->modifier();
      $this->assertTrue($ok);
    }
  }
}
// ============================================================================
?>
