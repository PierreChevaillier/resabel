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
 * description : Tests unitaires de la classe Seance_activite
 *               et de la classe Participation_Activite dont elle depend
 * utilisation : phpunit --testdox <chemin_vers_ce_fichier_php>
 * dependances :
 * - aucune
 * utilise avec :
 *    PHP 8.2 et PHPUnit 9.5 sur macOS 13.2
 * ----------------------------------------------------------------------------
 * creation : 06-fev-2023 pchevaillier@gmail.com
 * revision : 23-jan-2024 pchevaillier@gmail.com + peut_accueillir_participants
 * revision : 23-jan-2024 pchevaillier@gmail.com + creer_support, creer_seance
 * ----------------------------------------------------------------------------
 * commentaires :
 * - creer_support, creer_seance non utilisees pour l'instant
 * attention :
 * -
 * a faire :
 * -
 * ============================================================================
 */
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

// --------------------------------------------------------------------------
// --- Classes de l'environnement de test

set_include_path('./../../../');

require_once('php/metier/seance_activite.php'); // classe sous test

require_once('php/metier/support_activite.php');
require_once('php/metier/membre.php');

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

  private function creer_support(int $code_support, int $capacite, bool $resp_requis): Support_Activite {
    $support =  new Support_Activite($code_support);
    $type_support = new Type_Support_Activite(1);
    $support->def_type($type_support);
    $type_support->nombre_personnes_max = $capacite;
    $type_support->requiert_responsable($resp_requis);
    return $support;
  }
  
  private function creer_seance(Support_Activite $support,
                       bool $resp,
                       int $nombre_equipiers): Seance_Activite
  {
    $seance = new Seance_Activite();
    $this->seance->def_support($support);
    for ($i = 0; $i < $nombre_equipiers; $i++) {
      $participant = new Membre($i + 1); // attention : 0 code = zero est un cas particulier
      $participation = $seance->creer_participation($participant);
    }
    if ($resp) $seance->changer_responsable(1);
    return $seance;
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
    // aucun participant
    $this->assertEquals(0, $this->seance->nombre_participants());
    // 1 participant non responsable
    $membre = new Membre(1);
    $participation = $this->seance->creer_participation($membre);
    $this->assertEquals(1, $this->seance->nombre_participants());
    // 1 participant responsable
    $this->seance->changer_responsable($membre);
    $this->assertEquals(1, $this->seance->nombre_participants());
    // 2 participants dont 1 responsable
    $membre2 = new membre(2);
    $this->seance->creer_participation($membre2);
    $this->assertEquals(2, $this->seance->nombre_participants());
    // 2 participants dont aucun responsable
    $this->seance->annuler_responsable();
    $this->assertEquals(2, $this->seance->nombre_participants());
  }

  /**
   *
   */
  public function testNombreEquipiers(): void {
    // aucun participant
    $this->assertEquals(0, $this->seance->nombre_equipiers());
    // 1 participant non responsable
    $membre = new Membre(1);
    $participation = $this->seance->creer_participation($membre);
    $this->assertEquals(1, $this->seance->nombre_equipiers());
    // 1 participant responsable
    $this->seance->changer_responsable($membre);
    $this->assertEquals(0, $this->seance->nombre_equipiers());
    // 2 participants dont 1 responsable
    $membre2 = new membre(2);
    $this->seance->creer_participation($membre2);
    $this->assertEquals(1, $this->seance->nombre_equipiers());
    // 2 participants dont aucun responsable
    $this->seance->annuler_responsable();
    $this->assertEquals(2, $this->seance->nombre_equipiers());
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
  public function testNombrePlacesDisponiblesEtEquipiersDisponibles(): void {
    // definition du support de l'activite avec capacite non definie
    $support = new Support_Activite(512);
    $type_support = new Type_Support_Activite(1);
    $support->def_type($type_support);
    $this->seance->def_support($support);
    
    // aucune participation
    $this->assertNull($this->seance->nombre_places_disponibles());
    $this->assertNull($this->seance->nombre_places_equipiers_disponibles());
    
    $capacite = 2;
    $type_support->nombre_personnes_max = $capacite;
    
    // Pas de responsable requis - 1 participation - aucun responsable
    $membre = new Membre(1);
    $this->seance->creer_participation($membre);
    $this->assertEquals(1, $this->seance->nombre_places_disponibles());
    $this->assertEquals(1, $this->seance->nombre_places_equipiers_disponibles());
    
    // Responsable requis - 1 participation - aucun responsable
    $this->seance->support->type->requiert_responsable(true);
    $this->assertEquals(1, $this->seance->nombre_places_disponibles());
    $this->assertEquals(0, $this->seance->nombre_places_equipiers_disponibles());
    
    // Responsable requis - 1 participation - 1 responsable
    $this->seance->changer_responsable($membre);
    $this->assertEquals(1, $this->seance->nombre_places_disponibles());
    $this->assertEquals(1, $this->seance->nombre_places_equipiers_disponibles());

    // Pas de responsable requis - 2 participations
    $this->seance->support->type->requiert_responsable(false);
    $this->seance->annuler_responsable();
    $this->seance->creer_participation(new Membre(2));
    $this->assertEquals(0, $this->seance->nombre_places_disponibles());
    $this->assertEquals(0, $this->seance->nombre_places_equipiers_disponibles());
    
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
  
  /**
   *
   */
  public function testAccueilParticipantsSupportSansLimiteCapacite(): void {
    $code_support = 512;
    $support_accueil = new Support_Activite($code_support);
    $support_accueil->type = new Type_Support_Activite(1);
    
    $seance_accueil = new Seance_Activite();
    $seance_accueil->def_support($support_accueil);
    
    // seance a acceuillir sans aucune participation (= aucune inscription)
    // cas hors scenario d'usage
    $this->assertTrue($seance_accueil->peut_accueillir_participants($this->seance));
    
    // seance a accueillir avec 1 participation
    $participant = new Membre(101);
    $this->seance->creer_participation($participant);
    $this->assertTrue($seance_accueil->peut_accueillir_participants($this->seance));
  }
  
  /**
   *
   */
  public function testAccueilParticipantsSupportAvecLimiteCapaciteSansResponsableSeanceSurSeanceSansParticipation(): void {
    $code_support = 512;
    $support_accueil = new Support_Activite($code_support);
    $type_support = new Type_Support_Activite(1);
    $support_accueil->type = $type_support;
    $type_support->nombre_personnes_max = 2;
    
    $seance_accueil = new Seance_Activite();
    $seance_accueil->def_support($support_accueil);
    
    // seance a acceuillir sans aucune participation (= aucune inscription)
    // cas hors scenario d'usage
    $this->assertTrue($seance_accueil->peut_accueillir_participants($this->seance));
    
    // seance a accueillir avec nbre participation < capacite du support
    $participant = new Membre(101);
    $this->seance->creer_participation($participant);
    $this->assertTrue($seance_accueil->peut_accueillir_participants($this->seance));
    
    // seance a accueillir avec nbre participation = capacite du support
    $p2 = new Membre(102);
    $this->seance->creer_participation($p2);
    $this->assertTrue($seance_accueil->peut_accueillir_participants($this->seance));
    
    // seance a accueillir avec nbre participation > capacite du support
    $p3 = new Membre(103);
    $this->seance->creer_participation($p3);
    $this->assertFalse($seance_accueil->peut_accueillir_participants($this->seance));
  }
 
  /**
   *
   */
  public function testAccueilParticipantsSupportAvecLimiteCapaciteSansResponsableSeanceSurSeanceAvecParticipation(): void {
    $code_support = 512;
    $support_accueil = new Support_Activite($code_support);
    $type_support = new Type_Support_Activite(1);
    $support_accueil->type = $type_support;
    $type_support->nombre_personnes_max = 2;
    
    $seance_accueil = new Seance_Activite();
    $seance_accueil->def_support($support_accueil);
    $p1 = new Membre(1);
    $seance_accueil->creer_participation($p1);

    // seance a acceuillir sans aucune participation (= aucune inscription)
    // cas hors scenario d'usage
    $this->assertTrue($seance_accueil->peut_accueillir_participants($this->seance));
    
    // seance a accueillir avec nbre participation = nbre place dispo - 1
    $p2 = new Membre(2);
    $this->seance->creer_participation($p2);
    $this->assertTrue($seance_accueil->peut_accueillir_participants($this->seance));
    
    // seance a accueillir avec nbre participation = nbre place dispo
    $p3 = new Membre(3);
    $this->seance->creer_participation($p3);
    $this->assertFalse($seance_accueil->peut_accueillir_participants($this->seance));
  }
  
  /**
   *
   */
  public function testAccueilParticipantsSupportAvecLimiteCapaciteSurSeanceAvecResponsable(): void {
    // support d'activite recquierant 1 responsable
    $code_support = 512;
    $support_accueil = new Support_Activite($code_support);
    $type_support = new Type_Support_Activite(1);
    $support_accueil->type = $type_support;
    $type_support->nombre_personnes_max = 2;
    $type_support->requiert_responsable(true);
    
    
    // seance d'accueil avec 1 responsable
    $seance_accueil = new Seance_Activite();
    $seance_accueil->def_support($support_accueil);
    $p1 = new Membre(1);
    $est_responsable = true;
    $seance_accueil->creer_participation($p1, $est_responsable);

    // seance a accueillir sans aucune participation
    // cas hors scenario d'usage
    $this->assertTrue($seance_accueil->peut_accueillir_participants($this->seance));
    
    // seance a accueillir avec 1 participant non responsable
    $p2 = new Membre(2);
    $this->seance->creer_participation($p2);
    $this->assertTrue($seance_accueil->peut_accueillir_participants($this->seance));
    
    // seance a accueillir avec 1 participant non responsable
    $this->seance->changer_responsable($p2);
    $this->assertFalse($seance_accueil->peut_accueillir_participants($this->seance));
  }
  

  public function testAccueilParticipantSurBateauSolo(): void {

    $code_support = 512;
    $support_accueil = new Bateau($code_support);
    $type_support = new Type_Support_Activite(1);
    $support_accueil->type = $type_support;
    $type_support->nombre_personnes_max = 1;
    $type_support->requiert_responsable(true);
    
    // seance d'accueil sans personne
    $seance_accueil = new Seance_Activite();
    $seance_accueil->def_support($support_accueil);
    
    // seance a accueillir avec 1 responsable
    $p1 = new Membre(1);
    $est_responsable = true;
    $this->seance->creer_participation($p1, $est_responsable);
    $this->assertTrue($seance_accueil->peut_accueillir_participants($this->seance));
    
    // seance a accueillir sans responsable
    $this->seance->annuler_responsable();
    $this->assertFalse($seance_accueil->peut_accueillir_participants($this->seance));
  }

  /**
   *
   */
  public function testAccueilParticipantsSupportAvecLimiteCapaciteSurSeanceSansResponsable(): void {
    // support d'activite recquierant 1 responsable
    $code_support = 512;
    $support_accueil = new Support_Activite($code_support);
    $type_support = new Type_Support_Activite(1);
    $support_accueil->type = $type_support;
    $type_support->nombre_personnes_max = 2;
    $type_support->requiert_responsable(true);
    
    $est_responsable = true;
    // seance d'accueil sans responsable
    $seance_accueil = new Seance_Activite();
    $seance_accueil->def_support($support_accueil);
    $p1 = new Membre(1);
    $seance_accueil->creer_participation($p1);

    // seance a accueillir sans aucune participation
    // cas hors scenario d'usage
    $this->assertTrue($seance_accueil->peut_accueillir_participants($this->seance));
    
    // seance a accueillir avec nbre participation = nbre place dispo - 1 et avec responsable
    $p2 = new Membre(2);
    $this->seance->creer_participation($p2, $est_responsable);
    $this->assertTrue($seance_accueil->peut_accueillir_participants($this->seance));
    
    // seance a accueillir avec nbre participation = nbre place dispo - 1 et sans responsable
    $this->seance = new Seance_Activite();
    $this->seance->creer_participation($p2);
    $this->assertFalse($seance_accueil->peut_accueillir_participants($this->seance));

  }
}
// ============================================================================
?>
