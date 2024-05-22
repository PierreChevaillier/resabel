<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 *            Test unitaire
 * description :  Test unitaire de la classe metier Membre
 * copyright (c) 2014-2024 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : phpunit
 * dependances :
 * - classe de Resabel sous test : Specialisations d'Indisponibilite
 * utilise avec :
 * - depuis 2023 :
 *   PHP 8.2 sur macOS 13.x et PHPUnit 9.5
 * ----------------------------------------------------------------------------
 * creation : 28-fev-2024 pchevaillier@gmail.com
 * revision : 15-avr-2024 pchevaillier@gmail.com + methodes specialisations
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
// --- Classes de l'environement de test

// --- Classes de l'application
set_include_path('./../../../');

// --- Classe sous test
require_once('php/metier/indisponibilite.php');

// ============================================================================
/**
 * Test case.
 */
final class IndisponibiliteTest extends TestCase {
  
  private $mock;
  
  /**
   * Prepares the environment before running a test.
   */
  protected function setUp(): void {
    parent::setUp();
    $classname = 'Indisponibilite';

    // Get mock, without the constructor being called
    $this->mock = $this->getMockBuilder($classname)
      ->disableOriginalConstructor()
      ->getMockForAbstractClass();
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown(): void {
    parent::tearDown();
  }
  
  public function testConstructeurIndisponibilite(): void {
   
  // set expectations for constructor calls
  /*
  $mock->expects($this->once())
      ->method('setDoors')
      ->with(
              $this->equalTo(4)
            );
   */
    $code = 1;
          // now call the constructor
    /*          $reflectedClass = new ReflectionClass($classname);
              $constructor = $reflectedClass->getConstructor();
              $constructor->invoke($mock, $code);
     */
    
    $this->mock->__construct($code);

    $this->assertEquals($code, $this->mock->code());
    
    $nom_classe = "Fermeture_Site";
    $fermeture_site = new $nom_classe($code);
    $this->assertEquals($code, $fermeture_site->code());
    $nom_classe = "Indisponibilite_Support";
    $indispo = new $nom_classe($code);
    $this->assertEquals($code, $indispo->code());
  }
  
  public function testDefinitionPeriode(): void {
    $valeur_debut = '2024-02-27 10:24:31';
    $valeur_fin = '2024-02-28 11:24:31';
    $debut = new Instant($valeur_debut);
    $fin = new Instant($valeur_fin);
    
    $ok = $this->mock->definir_periode($debut, $fin);
    $this->assertTrue($ok);
    $this->assertEquals($valeur_debut, $this->mock->debut()->date_heure_sql());
    $this->assertEquals($valeur_debut, $this->mock->debut()->date_heure_sql());
    
    echo "\t" . $this->mock->formatter_periode() . PHP_EOL;
    
  }
  
  public function testDefinitionMotif(): void {
    $libelle_motif = $this->mock->libelle_motif();
    $this->assertEquals("motif non défini", $this->mock->libelle_motif());
    
    $libelle_motif = "Valeur test motif";
    $motif = new Motif_Indisponibilite(12);
    $motif->def_nom($libelle_motif);
    $this->mock->def_motif($motif);
    $this->assertEquals($libelle_motif, $this->mock->libelle_motif());
  }
  
  public function testDefinitionCreateurice(): void {
    $code_personne = 512;
    $prenom = 'Dominique';
    $nom = 'Personne';
    $p = new Personne($code_personne);
    $p->def_prenom($prenom);
    $p->def_nom($nom);
    $identite = $p->identite();
    
    $this->assertEquals("anonyme", $this->mock->identite_createurice());
    $this->mock->def_createurice($p);
    $this->assertEquals($identite, $this->mock->identite_createurice());
  }
  
  public function testDefinitionDateCreation(): void {
    $valeur = '2024-02-28 12:24:31';
    $date_heure = new Instant($valeur);
    $this->mock->def_instant_creation($date_heure);
    $this->assertEquals($valeur, $this->mock->instant_creation()->date_heure_sql());
  }
  
  public function testDefinitionSiteFerme(): void {
    // --- definition du site (classe abstraite)
    $code_site = 2;
    $nom_site = "Site activité";
    $site = $this->getMockBuilder('Site_Activite')
      ->disableOriginalConstructor()
      ->getMockForAbstractClass();
    $site->__construct($code_site);
    $site->def_nom($nom_site);
    
    // --- association site et Fermeture
    $code_indispo = 1;
    $fermeture = new Fermeture_Site($code_indispo);
    
    $this->assertNull($fermeture->code_objet());
    $this->assertNull($fermeture->libelle_objet());
    
    $fermeture->def_site_activite($site);
    
    // --- Verifications
    $this->assertEquals($code_site, $fermeture->code_objet());
    $this->assertEquals($nom_site, $fermeture->libelle_objet());
  }
  
  public function testDefinitionSupportIndisponible(): void {
    // --- definition du support (classe abstraite)
    $code_support = 33;
    $nom_support = "Nom du support";
    $support = $this->getMockBuilder('Support_Activite')
      ->disableOriginalConstructor()
      ->getMockForAbstractClass();
    $support->__construct($code_support);
    $support->def_nom($nom_support);
    
    // --- association support et Indisponibilite
    $code_indispo = 1;
    $indispo = new Indisponibilite_Support($code_indispo);
    
    $this->assertNull($indispo->code_objet());
    $this->assertNull($indispo->libelle_objet());
    
    $indispo->def_support($support);
    
    // --- Verifications
    $this->assertEquals($code_support, $indispo->code_objet());
    $this->assertEquals($nom_support, $indispo->libelle_objet());
  }

}
// ============================================================================
?>
