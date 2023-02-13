<?php
// ==========================================================================
// contexte : Resabel - systeme de REServAtion de Bateau En Ligne
//            Tests unitaires
// description : Test unitaire de la classe Seance_activite et
//  et de la classe Participation_Activite dont elle depend
// copyright (c) 2023 AMP. Tous droits reserves.
// --------------------------------------------------------------------------
// utilisation : phpunit --testdox <chemin_vers_ce_fichier_php>
// dependances :
// utilise avec :
//  - depuis 2023 :
//    PHP 8.2 et PHPUnit 9.5 sur macOS 13.2 ;
// --------------------------------------------------------------------------
// creation : 06-fev-2023 pchevaillier@gmail.com
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

// --- Classes de l'application
set_include_path('./../../../');

require_once('php/metier/seance_activite.php');

// ==========================================================================
/**
 * Test case.
 */
class Seance_ActiviteTest extends TestCase {

  private $seance;

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp(): void {
    parent::setUp();
    $this->seance = new Seance_Activite();
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
  public function testDefinirSupport(): void {
    $code_support = 512;
    $support = new Support_Activite($code_support);
    $this->seance->def_support($support);
    $this->assertEquals($code_support, $this->seance->code_support());
  }
  
  /**
   * 
   */
  public function testDefinirHoraire(): void {
    $t1 = new Instant("2023-11-25 21:32", new DateTimeZone('Europe/Paris'));
    $t2 = new Instant("2023-11-25 22:32", new DateTimeZone('Europe/Paris'));
    $this->seance->definir_horaire($t1, $t2);
    $debut = $this->seance->debut();
    $fin = $this->seance->fin();
    $this->assertEquals($debut, $t1);
    $this->assertEquals($fin, $t2);
  }

  /**
   * 
   */
  public function testResponsableRequis(): void {
    //$this->assertFalse($this->seance->responsable_requis());

    $support = new Support_Activite(512);
    $type_support = new Type_Support_Activite(1);
    $type_support->requiert_responsable(true);
    $support->def_type($type_support);
    $this->seance->def_support($support);
    $this->assertTrue($this->seance->responsable_requis());
    $type_support->requiert_responsable(false);
    $this->assertFalse($this->seance->responsable_requis());

  }

  /**
   * 
   */
  public function testCreerParticipations(): void {
    $membre = new Membre(1);
    $participation = $this->seance->creer_participation($membre);
    $this->assertNotNull($participation);
    $this->assertEquals($participation->participant, $membre);
    $this->assertEquals(1, $this->seance->nombre_participants());
    $this->assertTrue($this->seance->a_comme_participant($membre));

    $participation = $this->seance->creer_participation($membre);
    $this->assertEquals($participation->participant, $membre);
    $this->assertEquals(1, $this->seance->nombre_participants());
    $this->assertTrue($this->seance->a_comme_participant($membre));

    $membre2 = new Membre(2);
    $participation = $this->seance->creer_participation($membre2);
    $this->assertEquals($participation->participant, $membre2);
    $this->assertEquals(2, $this->seance->nombre_participants());
    $this->assertTrue($this->seance->a_comme_participant($membre2));
    $this->assertTrue($this->seance->a_comme_participant($membre));
    
    $support = new Support_Activite(1);
    $type_support = new Type_Support_Activite(1);
    $support->def_type($type_support);
    $this->seance->def_support($support);
    $capacite = 2;
    $type_support->nombre_personnes_max = $capacite;
    $membre3 = new Membre(3);
    $participation = $this->seance->creer_participation($membre3);
    $this->assertNull($participation);
    $this->assertFalse($this->seance->a_comme_participant($membre3));
  }

  /**
   * 
   */
  public function testNombreParticipants(): void {
    $this->assertEquals(0, $this->seance->nombre_participants());
    $membre = new Membre(1);
    $participation = $this->seance->creer_participation($membre);
    $this->assertEquals(1, $this->seance->nombre_participants());
  }

  /**
   *
   */
  public function testNombrePlaceEstLimite(): void {
    // Support defini mais sans type
    $support = new Support_Activite(1);
    $this->seance->def_support($support);
    $this->assertFalse($this->seance->nombre_places_est_limite());

    // Type de support defini mais capacite non definie
    $type_support = new Type_Support_Activite(1);
    $support->def_type($type_support);
    $this->assertFalse($this->seance->nombre_places_est_limite());
    
    // Type de support defini avec capacite non definie
    $this->seance->def_support($support);
    $capacite = 2;
    $type_support->nombre_personnes_max = $capacite;
    $this->assertTrue($this->seance->nombre_places_est_limite());
  }
  /**
   * 
   */
  public function testNombrePlacesDisponibles(): void {
    // definition du support de l'activite avec capacite non definie
    $support = new Support_Activite(512);
    $type_support = new Type_Support_Activite(1);
    $support->def_type($type_support);
    $this->seance->def_support($support);
    $this->assertNull($this->seance->nombre_places_disponibles());
    
    $capacite = 2;
    $type_support->nombre_personnes_max = $capacite;
    
    $this->seance->creer_participation(new Membre(1));
    $this->assertEquals(1, $this->seance->nombre_places_disponibles());
    
    $this->seance->creer_participation(new Membre(2));
    $this->assertEquals(0, $this->seance->nombre_places_disponibles());
    
    $this->seance->creer_participation(new Membre(3));
    $this->assertEquals(0, $this->seance->nombre_places_disponibles());
  }

  /**
   * 
   */
  public function testChangerResponsable(): void {
    $membre = new Membre(1);
    $est_responsable = false;
    $this->seance->creer_participation($membre, $est_responsable);
    $this->assertTrue($this->seance->changer_responsable($membre));
    $this->assertTrue($this->seance->a_comme_responsable($membre));

    $membre2 = new Membre(2);
    $this->assertFalse($this->seance->changer_responsable($membre2));
    $this->assertTrue($this->seance->a_comme_responsable($membre));

    $est_responsable = false;
    $this->seance->creer_participation($membre2, $est_responsable);
    $this->assertTrue($this->seance->changer_responsable($membre2));
    $this->assertFalse($this->seance->a_comme_responsable($membre));
  }

  /**
   * 
   */
  public function testAnnulerResponsable(): void {
    $membre = new Membre(1);
    $est_responsable = true;
    $this->seance->creer_participation($membre, $est_responsable);
    $this->seance->annuler_responsable();
    $this->assertFalse($this->seance->a_comme_responsable($membre));
  }

  /**
   * 
   */
  public function testAUnResponsable(): void {
    $this->assertFalse($this->seance->a_un_responsable());

    $membre = new Membre(1);
    $est_responsable = false;
    $this->seance->creer_participation($membre, $est_responsable);
    $this->assertFalse($this->seance->a_un_responsable());

    $est_responsable = true;
    $this->seance->creer_participation($membre, $est_responsable);
    $this->assertTrue($this->seance->a_un_responsable());

  }

  /**
   * 
   */
  public function testACommeResponsable(): void {
    // Seance sans aucune participation
    $membre = new Membre(1);
    $this->assertFalse($this->seance->a_comme_responsable($membre));

    // une participation (pas responsable)
    $est_responsable = false;
    $this->seance->creer_participation($membre, $est_responsable);
    $this->assertFalse($this->seance->a_comme_responsable($membre));

    // avec participation d'un responsable
    $est_responsable = true;
    $this->seance->creer_participation($membre, $est_responsable);
    $this->assertTrue($this->seance->a_comme_responsable($membre));

    // l'evaluation de la condition porte sur l'identite de la personne
    // et non sur l'egalite des references des objets
    $membre2 = new Membre(1);
    $this->assertTrue($this->seance->a_comme_responsable($membre2));

    // Changement de responsable 
    $membre3 = new Membre(2);
    $est_responsable = true;
    $this->seance->creer_participation($membre3, $est_responsable);
    $this->assertTrue($this->seance->a_comme_responsable($membre3));
    $this->assertFalse($this->seance->a_comme_responsable($membre));

  }
}
// ==========================================================================
?>
