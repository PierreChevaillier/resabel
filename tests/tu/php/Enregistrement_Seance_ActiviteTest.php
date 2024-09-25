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
 * description : Tests fonctionnels unitaires de la classe Enregistrement_Seance_Activite
 * utilisation : phpunit --testdox <chemin_vers_ce_fichier_php>
 * dependances :
 * - structure table
 * utilise avec :
 * - PHP 8.2 et PHPUnit 9.5 sur macOS 13.6
 * ----------------------------------------------------------------------------
 * creation : 07-fev-2023 pchevaillier@gmail.com
 * revision : 25-sep-2024 pchevaillier@gmail.com + ajouts de test
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * -
 * a faire :
 * - a completer
 * - collecter participants creneau horaire (couverture + fonctionnalite)
 * - collecter (nombreuses options > enriente couverture du code)
 * ============================================================================
 */

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

// ----------------------------------------------------------------------------
// --- Classes de l'enviromment de test
include_once './base_donnees.php';

// --- Classes de l'application
set_include_path('./../../../');

// classe sous test
require_once('php/bdd/enregistrement_seance_activite.php');

// autres classes
require_once 'php/metier/calendrier.php';
require_once 'php/metier/seance_activite.php';

// ============================================================================
/**
 * Test case.
 */
class Enregistrement_Seance_activiteTest extends TestCase {

  private static ?PDO $bdd = null;
  
  private $enregistrement;
  private $participations = array();

  public static function setUpBeforeClass(): void {
    print("Test case: Connection to the database" . PHP_EOL);
    self::$bdd = Base_Donnees::acces();
  }

  public static function tearDownAfterClass(): void {
    print("Test case: Delete connection handler" . PHP_EOL);
    self::$bdd = null;
  }
  
  /**
   * Prepares the environment before running a test.
   */
  protected function setUp(): void {
    parent::setUp();
    $participations = [];
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown(): void {
    foreach ($this->participations as $p) {
      Enregistrement_Seance_Activite::supprimer_participation($p);
    }
    parent::tearDown();
  }

  private function code_derniere_seance(): int {
    $resultat = 0;
    $source = PREFIX_TABLE . "seances_activite";
    try {
      $requete = "SELECT MAX(code) AS x FROM " . $source;
      $resultat = self::$bdd->query($requete);
      $donnee = $resultat->fetch(PDO::FETCH_OBJ);
      $resultat = is_null($donnee->x)? 0: $donnee->x;
    } catch (PDOException $e) {
      Base_Donnees::sortir_sur_exception($source, $e);
    }
    return $resultat;
  }
  
  private function compter_seance(int $code_seance): int {
    $resultat = -1;
    $source = PREFIX_TABLE . "seances_activite";
    try {
      $requete = "SELECT COUNT(*) AS n FROM " . $source
        . " WHERE code = " . $code_seance;
      $resultat = self::$bdd->query($requete);
      $donnee = $resultat->fetch(PDO::FETCH_OBJ);
      $resultat = $donnee->n;
    } catch (PDOException $e) {
      Base_Donnees::sortir_sur_exception($source, $e);
    }
    return $resultat;
  }
  
  private function seance_a_comme_responsable(int $code_seance,
                                              int $code_participant): bool {
    $condition = false;
    $source = PREFIX_TABLE . "seances_activite";
    try {
      $bdd = base_donnees::acces();
      $requete = "SELECT code_responsable FROM " . $source
        . " WHERE code = " . $code_seance;
      $resultat = $bdd->query($requete);
      $donnee = $resultat->fetch(PDO::FETCH_OBJ);
      $condition = ($donnee->code_responsable == $code_participant);
    } catch (PDOException $e) {
      Base_Donnees::sortir_sur_exception($source, $e);
    }
    return $condition;
  }
  
  private function seance_a_comme_support(int $code_seance,
                                          int $code_support): bool {
    $condition = false;
    $source = PREFIX_TABLE . "seances_activite";
    try {
      $bdd = base_donnees::acces();
      $requete = "SELECT code_support FROM " . $source
        . " WHERE code = " . $code_seance;
      $resultat = $bdd->query($requete);
      $donnee = $resultat->fetch(PDO::FETCH_OBJ);
      $condition = ($donnee->code_support == $code_support);
    } catch (PDOException $e) {
      Base_Donnees::sortir_sur_exception($source, $e);
    }
    return $condition;
  }
  
  private function seance_sans_responsable(int $code_seance): bool {
    $condition = false;
    $source = PREFIX_TABLE . "seances_activite";
    try {
      $requete = "SELECT code_responsable FROM " . $source
        . " WHERE code = " . $code_seance;
      $resultat = self::$bdd->query($requete);
      $donnee = $resultat->fetch(PDO::FETCH_OBJ);
      $condition = ($donnee->code_responsable == NULL);
    } catch (PDOException $e) {
      Base_Donnees::sortir_sur_exception($source, $e);
    }
    return $condition;
  }
  
  private function compter_participations_seance(int $code_seance): int {
    $resultat = -1;
    $source = PREFIX_TABLE . "participations_activite";
    try {
      $requete = "SELECT COUNT(*) AS n FROM " . $source
        . " WHERE code_seance = " . $code_seance;
      $resultat_requete = self::$bdd->query($requete);
      $donnee = $resultat_requete->fetch(PDO::FETCH_OBJ);
      $resultat = $donnee->n;
    } catch (PDOException $e) {
      Base_Donnees::sortir_sur_exception($source, $e);
    }
    return $resultat;
  }

  /**
   * Teste le nom de la table source des informations
   */
  public function testSourceTableSeancesActivite(): void {
    $this->assertEquals(PREFIX_TABLE . 'seances_activite', Enregistrement_Seance_Activite::source());
  }

  public function testVerificationSeanceExiste(): void {
    $valeur_invalide = 0;
    $ok = Enregistrement_Seance_Activite::seance_existe($valeur_invalide); // methode sous test
    $this->assertFalse($ok);
    
    // cas ou la seance existe
    $jour = Calendrier::aujourdhui();
    $une_heure = new DateInterval('PT1H0M0S');
    $debut = $jour;
    $debut= $jour->add(new DateInterval('PT7H0M0S'));
    $fin = $debut->add($une_heure);
    
    $p1 = new Information_Participation_Seance_Activite();
    $p1->code_site = 1;
    $p1->code_support_activite = 1;
    $p1->code_participant = 101;
    $p1->debut = $debut->date_heure_sql();
    $p1->fin = $fin->date_heure_sql();
    
    Enregistrement_Seance_Activite::ajouter_participation($p1);
    $this->participations[] = $p1;
    
    $ok = Enregistrement_Seance_Activite::seance_existe($p1->code_seance); // methode sous test
    $this->assertTrue($ok);
    
    // cas ou la seance n'existe pas
    $code_seance_inexistante = $this->code_derniere_seance() + 1;
    $ok = Enregistrement_Seance_Activite::seance_existe($code_seance_inexistante); // methode sous test
    $this->assertFalse($ok);
    
  }
  
  public function testAjouterParticipationMembreDisponible(): void {
    $jour = Calendrier::aujourdhui();
    $une_heure = new DateInterval('PT1H0M0S');
    $debut = $jour;
    $debut= $jour->add(new DateInterval('PT7H0M0S'));
    $fin = $debut->add($une_heure);
    
    $p1 = new Information_Participation_Seance_Activite();
    $p1->code_site = 1;
    $p1->code_support_activite = 1;
    $p1->code_participant = 101;
    $p1->debut = $debut->date_heure_sql();
    $p1->fin = $fin->date_heure_sql();
    
    $status = Enregistrement_Seance_Activite::ajouter_participation($p1); // methode sous test
    $this->assertEquals(1, $status);
    
    $code_seance = $this->code_derniere_seance();
    $this->assertEquals($code_seance, $p1->code_seance);
    
    // Suppression effets de bord du test
    Enregistrement_Seance_Activite::supprimer_seance($code_seance);
  }
  
  public function testAjouterParticipationMembreIndisponible(): void {
    $jour = Calendrier::aujourdhui();
    $une_heure = new DateInterval('PT1H0M0S');
    $debut = $jour;
    $debut= $jour->add(new DateInterval('PT7H0M0S'));
    $fin = $debut->add($une_heure);
    
    $p1 = new Information_Participation_Seance_Activite();
    $p1->code_site = 1;
    $p1->code_support_activite = 1;
    $p1->code_participant = 101;
    $p1->debut = $debut->date_heure_sql();
    $p1->fin = $fin->date_heure_sql();
    
    $status = Enregistrement_Seance_Activite::ajouter_participation($p1);
    $code1 = $p1->code_seance;

    // nouvelle seance, meme participant, meme creneau, autre support
    $p1->code_seance = 0;
    $p1->code_support_activite = 2;
    $status = Enregistrement_Seance_Activite::ajouter_participation($p1);
    $this->assertEquals(7, $status);
    
    $this->assertEquals(0, $p1->code_seance); // seance non creee
    $code_seance = $this->code_derniere_seance();
    $this->assertEquals($code_seance, $code1);
    
    // Suppression effets de bord du test
    Enregistrement_Seance_Activite::supprimer_seance($code1);
  }

  public function testDispoMembre(): void {
    $jour = Calendrier::aujourdhui();
    $une_heure = new DateInterval('PT1H0M0S');
    $debut = $jour;
    $debut= $jour->add(new DateInterval('PT7H0M0S'));
    $fin = $debut->add($une_heure);
    
    $seances_creees = array();
    
    $p1 = new Information_Participation_Seance_Activite();
    $p1->code_site = 1;
    $p1->code_support_activite = 1;
    $p1->code_participant = 101;
    $p1->debut = $debut->date_heure_sql();
    $p1->fin = $fin->date_heure_sql();
    
    // cas sans participation, donc membre dispo
    $dispo = Enregistrement_Seance_Activite::verifier_disponibilite_membre($p1); // methode sous test
    $this->assertTrue($dispo);

    // --- cas avec d'autres participations
    // Autre personne, meme creneau - autre support (donc autre seance)
    $p2 = new Information_Participation_Seance_Activite();
    $p2->code_site = 1;
    $p2->code_support_activite = 2;
    $p2->code_participant = 102;
    $p2->debut = $p1->debut;
    $p2->fin = $p1->fin;
    Enregistrement_Seance_Activite::ajouter_participation($p2);
    $seances_creees[] = $p2->code_seance;
    
    $dispo = Enregistrement_Seance_Activite::verifier_disponibilite_membre($p1); // methode sous test
    $this->assertTrue($dispo);

    // --- Autre personne, meme creneau, meme support (donc meme seance)
    $p2->code_seance = $p1->code_seance;
    $p2->code_support_activite = $p1->code_support_activite;
    $p2->code_participant = 103;
    Enregistrement_Seance_Activite::ajouter_participation($p2);
    $seances_creees[] = $p2->code_seance;
    
    $dispo = Enregistrement_Seance_Activite::verifier_disponibilite_membre($p1); // methode sous test
    $this->assertTrue($dispo);
    
    // --- Meme personne, meme support, creneaux adjacents (autres seances)
    $p2->code_seance = 0;
    $p2->code_participant = $p1->code_participant;
    $p2->code_support_activite = $p1->code_support_activite;
    $debut2 = $debut->sub($une_heure);
    $fin2 = $fin->add($une_heure);
    
    $p2->debut = $debut2->date_heure_sql();
    $p2->fin = $p1->debut;
    Enregistrement_Seance_Activite::ajouter_participation($p2);
    $seances_creees[] = $p2->code_seance;
    
    $p2->code_seance = 0;
    $p2->debut = $p1->fin;
    $p2->fin = $fin2->date_heure_sql();
    Enregistrement_Seance_Activite::ajouter_participation($p2);
    $seances_creees[] = $p2->code_seance;

    $dispo = Enregistrement_Seance_Activite::verifier_disponibilite_membre($p1); // methode sous test
    $this->assertTrue($dispo, "cas participations même supprt, créneaux adjacents");
    
    // ------------------------------------------------------------------------
    // Meme personne, meme creneau, autre support, donc membre non dispo
    
    $p2->code_seance = 0;
    $p2->code_participant = $p1->code_participant;
    $p2->code_support_activite = 3;
    $p2->debut = $p1->debut;
    $p2->fin = $p1->fin;
    Enregistrement_Seance_Activite::ajouter_participation($p2);
    $seances_creees[] = $p2->code_seance;
    
    $dispo = Enregistrement_Seance_Activite::verifier_disponibilite_membre($p1); // methode sous test
    $this->assertFalse($dispo, "cas participation sur le même créneaux");
    
    // Suppression effets de bord du test (seances et participations associees)
    foreach ($seances_creees as $code_seance)
      Enregistrement_Seance_Activite::supprimer_seance($code_seance);
  }
  
  public function testCompleterEquipage(): void {
    $jour = Calendrier::aujourdhui();
    $une_heure = new DateInterval('PT1H0M0S');
    $debut = $jour;
    $debut= $jour->add(new DateInterval('PT7H0M0S'));
    $fin = $debut->add($une_heure);
    
    $p1 = new Information_Participation_Seance_Activite();
    $p1->code_site = 1;
    $p1->code_support_activite = 1;
    $p1->code_participant = 101;
    $p1->debut = $debut->date_heure_sql();
    $p1->fin = $fin->date_heure_sql();
    
    $status = Enregistrement_Seance_Activite::ajouter_participation($p1); // methode sous test
    $this->assertEquals(1, $status);
    
    $code_seance = $this->code_derniere_seance();
    $this->assertEquals($code_seance, $p1->code_seance);
    
    $p1->code_participant = 60;
    $status = Enregistrement_Seance_Activite::ajouter_participation($p1); // methode sous test
    $this->assertEquals(1, $status);
    $this->assertEquals($code_seance, $p1->code_seance);

    $p1->code_participant = 61;
    $p1->responsable = 1;
    $status = Enregistrement_Seance_Activite::ajouter_participation($p1); // methode sous test
    $this->assertEquals(1, $status);
    $this->assertEquals($code_seance, $p1->code_seance);

    // Suppression effets de bord du test
    Enregistrement_Seance_Activite::supprimer_seance($code_seance);
  }

  public function testCompterParticipations(): void {
    
    $jour = Calendrier::aujourdhui();
    $une_heure = new DateInterval('PT1H0M0S');
    $debut = $jour;
    $debut= $jour->add(new DateInterval('PT7H0M0S'));
    $fin = $debut->add($une_heure);
    
    // quand aucune information particuliere n'est donnee
    $p0 = new Information_Participation_Seance_Activite();
    $this->participations[] = $p0;
    $nombre_effectif = Enregistrement_Seance_Activite::compter_participations($p0);
    $this->assertEquals(0, $nombre_effectif, "sans info, ce nombre est zero");
    
    // quand on specifie les informations de l'unique participation a la seance
    // et que celle-ci existe, alors on en compte une
    $p1 = new Information_Participation_Seance_Activite();
    $this->participations[] = $p1;
    $p1->code_site = 1;
    $p1->code_support_activite = 1;
    $p1->code_participant = 999;
    $p1->debut = $debut->date_heure_sql();
    $p1->fin = $fin->date_heure_sql();
    
    Enregistrement_Seance_Activite::ajouter_participation($p1);
    $nombre_effectif = Enregistrement_Seance_Activite::compter_participations($p1);
    $this->assertEquals(1, $nombre_effectif, "enregistrement participation activite");
    
    // quand il y a plusieurs participations dans la seance
    // alors on n'en compte qu'une par participant
    $p2 = new Information_Participation_Seance_Activite();
    $this->participations[] = $p2;
    $p2->code_site = 1;
    $p2->code_support_activite = 1;
    $p2->code_participant = 888;
    $p2->responsable = 1;
    $p2->debut = $debut->date_heure_sql();
    $p2->fin = $fin->date_heure_sql();
    $p2->code_seance = $p1->code_seance; // pour qu'il s'agisse de participations a la meme seance
    
    Enregistrement_Seance_Activite::ajouter_participation($p2);
    $nombre_effectif = Enregistrement_Seance_Activite::compter_participations($p2);
    $this->assertEquals(1, $nombre_effectif, "enregistrement participation activite");
   
   // Suppression effets de bord du test
   Enregistrement_Seance_Activite::supprimer_seance($p1->code_seance);
   
  }
  
  public function testSupprimerParticipation(): void {

    // TODO : tester que
    // quand on supprime la participation du responsable
    // alors il faut renseigner que la seance n'a pas de responsable.
    
    $jour = Calendrier::aujourdhui();
    $une_heure = new DateInterval('PT1H0M0S');
    $debut = $jour;
    $debut= $jour->add(new DateInterval('PT7H0M0S'));
    $fin = $debut->add($une_heure);

    // quand il y a plusieurs participations
    $p1 = new Information_Participation_Seance_Activite();
    $p1->code_site = 1;
    $p1->code_support_activite = 1;
    $p1->code_participant = 999;
    $p1->debut = $debut->date_heure_sql();
    $p1->fin = $fin->date_heure_sql();
    Enregistrement_Seance_Activite::ajouter_participation($p1);
    $seances_creees[] = $p1->code_seance;
    
    $p2 = new Information_Participation_Seance_Activite();
    $p2->code_seance = $p1->code_seance;
    $p2->code_site = $p1->code_site;
    $p2->code_support_activite = $p1->code_support_activite;
    $p2->code_participant = 888;
    $p2->debut = $p1->debut;
    $p2->fin = $p1->fin;
    Enregistrement_Seance_Activite::ajouter_participation($p2);
    
    $status = Enregistrement_Seance_Activite::supprimer_participation($p2);
    $np_apres = $this->compter_participations_seance($p1->code_seance);
    $ns_apres = $this->compter_seance($p1->code_seance);
    $this->assertTrue($status, "tentative suppression une des participations");
    $this->assertEquals(1, $np_apres, "il reste 1 participation");
    $this->assertEquals(1, $ns_apres, "la seance existe toujours");
    
    // quand il y a une seule participation
    $status = Enregistrement_Seance_Activite::supprimer_participation($p1);
    $np_apres = $this->compter_participations_seance($p1->code_seance);
    $this->assertTrue($status, "tentative suppression de la (seule) participation");
    $this->assertEquals(0, $np_apres, "il n'y a plus de participation");
    $ns_apres = $this->compter_seance($p1->code_seance);
    $this->assertEquals(0, $ns_apres, "plus aucune participation donc la seance est supprimee");

    // quand il n'y a aucune participation, donc aucune seance
    $status = Enregistrement_Seance_Activite::supprimer_participation($p1);
    $this->assertTrue($status, "tentative suppression participation inexistante");
  }

  public function testSupprimerSeance(): void {

    $jour = Calendrier::aujourdhui();
    $une_heure = new DateInterval('PT1H0M0S');
    $debut = $jour;
    $debut= $jour->add(new DateInterval('PT7H0M0S'));
    $fin = $debut->add($une_heure);
    
    // quand il y a plusieurs participations a la seance
    $p1 = new Information_Participation_Seance_Activite();
    $p1->code_site = 1;
    $p1->code_support_activite = 1;
    $p1->code_participant = 999;
    $p1->debut = $debut->date_heure_sql();
    $p1->fin = $fin->date_heure_sql();
    
    $p2 = new Information_Participation_Seance_Activite();
    $p1->code_site = 1;
    $p1->code_support_activite = 1;
    $p1->code_participant = 888;
    $p2->debut = $debut->date_heure_sql();
    $p2->fin = $fin->date_heure_sql();
    
    Enregistrement_Seance_Activite::ajouter_participation($p1);
    //print("Code seance :" . $p1->code_seance);
    $p2->code_seance = $p1->code_seance;
    Enregistrement_Seance_Activite::ajouter_participation($p2);
    
    $status = Enregistrement_Seance_Activite::supprimer_seance($p2->code_seance);
    $np_apres = $this->compter_participations_seance($p1->code_seance);
    $ns_apres = $this->compter_seance($p1->code_seance);
    $this->assertTrue($status, "tentative suppression seance avec plusieurs participations");
    $this->assertEquals(0, $np_apres, "plus aucune participation a l'activite");
    $this->assertEquals(0, $ns_apres, "activite supprimee");
   
    // quand la seance n'existe pas
    $status = Enregistrement_Seance_Activite::supprimer_seance($p2->code_seance);
    $this->assertTrue($status, "tentative suppression seance inexistante");
}

  public function testAjouterParticipationsEquipier(): void {
    $jour = Calendrier::aujourdhui();
    $une_heure = new DateInterval('PT1H0M0S');
    $debut = $jour;
    $debut= $jour->add(new DateInterval('PT7H0M0S'));
    $fin = $debut->add($une_heure);
    
    // Premiere participation a la seance : creation participation et seance
    $p1 = new Information_Participation_Seance_Activite();
    $p1->code_site = 1;
    $p1->code_support_activite = 1;
    $p1->code_participant = 999;
    $p1->debut = $debut->date_heure_sql();
    $p1->fin = $fin->date_heure_sql();
    
    $status = Enregistrement_Seance_Activite::ajouter_participation($p1);
    //print("seance: " . $p1->code_seance . PHP_EOL);
    $this->participations[] = $p1;

    $this->assertEquals(1, $status);
    $np_apres = $this->compter_participations_seance($p1->code_seance);
    $ns_apres = $this->compter_seance($p1->code_seance);
    $this->assertEquals(1, $np_apres, "enregistrement participation activite");
    $this->assertEquals(1, $ns_apres, "enregistrement seance activite");
    $ok = $this->seance_sans_responsable($p1->code_seance);
    $this->assertTrue($ok, "seance sans responsable");
    
    // Deuxieme participation a la meme seance : creation de la participation
    $p2 = new Information_Participation_Seance_Activite();
    $p2->code_seance = $p1->code_seance;
    $p2->code_site = 1;
    $p2->code_support_activite = 1;
    $p2->code_participant = 888;
    $p2->debut = $debut->date_heure_sql();
    $p2->fin = $fin->date_heure_sql();
    
    $status = Enregistrement_Seance_Activite::ajouter_participation($p2);
    //print("seance: " . $p2->code_seance . PHP_EOL);
    $this->assertEquals($p1->code_seance, $p2->code_seance);
    $this->participations[] = $p2;
    $np_apres = $this->compter_participations_seance($p2->code_seance);
    $ns_apres = $this->compter_seance($p2->code_seance);
    $this->assertEquals(2, $np_apres, "enregistrements participation activite");
    $this->assertEquals(1, $ns_apres, "enregistrement seance activite");
    $ok = $this->seance_sans_responsable($p2->code_seance);
    $this->assertTrue($ok, "seance sans responsable");
   
    // tentative d'ajouter deux fois la meme participation
    $status = Enregistrement_Seance_Activite::ajouter_participation($p2);
    $this->assertEquals(1, $status);
    
    $np_apres = $this->compter_participations_seance($p2->code_seance);
    $this->assertEquals(2, $np_apres, "enregistrements participation activite");
    
  }

  public function testAjouterParticipationsResponsable(): void {
    $jour = Calendrier::aujourdhui();
    $une_heure = new DateInterval('PT1H0M0S');
    $debut = $jour;
    $debut= $jour->add(new DateInterval('PT7H0M0S'));
    $fin = $debut->add($une_heure);
    
    // Premiere participation a la seance : creation participation et seance
    $p1 = new Information_Participation_Seance_Activite();
    $p1->code_site = 1;
    $p1->code_support_activite = 1;
    $p1->code_participant = 999;
    $p1->responsable = 1;
    $p1->debut = $debut->date_heure_sql();
    $p1->fin = $fin->date_heure_sql();
    
    $status = Enregistrement_Seance_Activite::ajouter_participation($p1);
    //print("seance: " . $p1->code_seance . PHP_EOL);
    $this->participations[] = $p1;

    $this->assertEquals(1, $status);
    $ok = $this->seance_a_comme_responsable($p1->code_seance,
                                            $p1->code_participant);
    $this->assertTrue($ok, "seance avec responsable");
    
    // nouvelle particpation avec resonsapble : MaJ du responsable
    $p2 = new Information_Participation_Seance_Activite();
    $p2->code_seance = $p1->code_seance;
    $p2->code_site = 1;
    $p2->code_support_activite = 1;
    $p2->code_participant = 888;
    $p2->responsable = 1;
    $p2->debut = $debut->date_heure_sql();
    $p2->fin = $fin->date_heure_sql();
    
    $status = Enregistrement_Seance_Activite::ajouter_participation($p2);
    //print("seance: " . $p1->code_seance . PHP_EOL);
    $this->participations[] = $p2;

    $this->assertEquals(1, $status);
    $ok = $this->seance_a_comme_responsable($p2->code_seance,
                                            $p2->code_participant);
    $this->assertTrue($ok, "seance avec nouveau responsable");
    
  }

  public function testSupprimerParticipationResponsable(): void {
    $jour = Calendrier::aujourdhui();
    $une_heure = new DateInterval('PT1H0M0S');
    $debut = $jour;
    $debut= $jour->add(new DateInterval('PT7H0M0S'));
    $fin = $debut->add($une_heure);

    $p1 = new Information_Participation_Seance_Activite();
    $p1->code_site = 1;
    $p1->code_support_activite = 1;
    $p1->code_participant = 999;
    $p1->debut = $debut->date_heure_sql();
    $p1->fin = $fin->date_heure_sql();
    
    $p2 = new Information_Participation_Seance_Activite();
    $p2->code_site = 1;
    $p2->code_support_activite = 1;
    $p2->code_participant = 888;
    $p2->responsable = 1;
    $p2->debut = $debut->date_heure_sql();
    $p2->fin = $fin->date_heure_sql();
    
    Enregistrement_Seance_Activite::ajouter_participation($p1);
    $this->participations[] = $p1;
    $p2->code_seance = $p1->code_seance;
    Enregistrement_Seance_Activite::ajouter_participation($p2);
    $status = Enregistrement_Seance_Activite::supprimer_participation($p2); // methode sous test
    $ok = $this->seance_sans_responsable($p2->code_seance);
    $this->assertTrue($ok, "seance sans responsable");
  }

  public function testBasculeEquipierResponsable(): void {
    $jour = Calendrier::aujourdhui();
    $une_heure = new DateInterval('PT1H0M0S');
    $debut = $jour;
    $debut= $jour->add(new DateInterval('PT7H0M0S'));
    $fin = $debut->add($une_heure);

    $p1 = new Information_Participation_Seance_Activite();
    $p1->code_site = 1;
    $p1->code_support_activite = 1;
    $p1->code_participant = 999;
    $p1->debut = $debut->date_heure_sql();
    $p1->fin = $fin->date_heure_sql();
    
    $p2 = new Information_Participation_Seance_Activite();
    $p2->code_site = 1;
    $p2->code_support_activite = 1;
    $p2->code_participant = 888;
    $p2->responsable = 1;
    $p2->debut = $debut->date_heure_sql();
    $p2->fin = $fin->date_heure_sql();
    
    Enregistrement_Seance_Activite::ajouter_participation($p1);
    $p2->code_seance = $p1->code_seance;
    $this->participations[] = $p1;
    Enregistrement_Seance_Activite::ajouter_participation($p2);
    $this->participations[] = $p2;

    // changement de responsable
    $status = Enregistrement_Seance_Activite::passer_equipier_responsable($p2->code_seance, $p1->code_participant);
    $ok = $this->seance_a_comme_responsable($p2->code_seance, $p1->code_participant);
    $this->assertTrue($ok, "nouveau responsable : " . $p1->code_participant);

    // changement de responsable vers lui meme
    $status = Enregistrement_Seance_Activite::passer_equipier_responsable($p2->code_seance, $p1->code_participant);
    $ok = $this->seance_a_comme_responsable($p2->code_seance, $p1->code_participant);
    $this->assertTrue($ok, "pas de changement responsable : " . $p1->code_participant);

    // passe le responable equipier
    $status = Enregistrement_Seance_Activite::passer_responsable_equipier($p2->code_seance);
    $ok = $this->seance_sans_responsable($p2->code_seance);
    $this->assertTrue($ok, "seance sans responsable");
    
    // tentative de changement de responsable pour une seance sans responsable
    $status = Enregistrement_Seance_Activite::passer_responsable_equipier($p2->code_seance);
    $ok = $this->seance_sans_responsable($p2->code_seance);
    $this->assertTrue($ok, "seance sans responsable");
  }
  
  public function testchangementHoraire(): void {
    $jour = Calendrier::aujourdhui();
    $une_heure = new DateInterval('PT1H0M0S');
    $debut = $jour;
    $debut= $jour->add(new DateInterval('PT7H0M0S'));
    $fin = $debut->add($une_heure);
    
    $p1 = new Information_Participation_Seance_Activite();
    $p1->code_site = 1;
    $p1->code_support_activite = 1;
    $p1->code_participant = 999;
    $p1->debut = $debut->date_heure_sql();
    $p1->fin = $fin->date_heure_sql();
    
    Enregistrement_Seance_Activite::ajouter_participation($p1);
    $this->participations[] = $p1;
    
    $jour = Calendrier::aujourdhui();
    $debut_nouveau = $jour->add(new DateInterval('PT5H0M0S'));
    $fin_nouveau = $debut_nouveau->add($une_heure);
    $p1->debut = $debut_nouveau->date_heure_sql();
    $p1->fin = $fin_nouveau->date_heure_sql();
    $status = Enregistrement_Seance_Activite::changer_horaire($p1->code_seance,
                                                              $p1->debut,
                                                              $p1->fin);
    $this->assertTrue($status, "MaJ horaire seance effectuee");
    
    }
  
  public function testchangementSupportActivite(): void {
    $jour = Calendrier::aujourdhui();
    $une_heure = new DateInterval('PT1H0M0S');
    $debut = $jour;
    $debut= $jour->add(new DateInterval('PT7H0M0S'));
    $fin = $debut->add($une_heure);
    
    $p1 = new Information_Participation_Seance_Activite();
    $p1->code_site = 1;
    $p1->code_support_activite = 1;
    $p1->code_participant = 999;
    $p1->debut = $debut->date_heure_sql();
    $p1->fin = $fin->date_heure_sql();
    
    Enregistrement_Seance_Activite::ajouter_participation($p1);
    $this->participations[] = $p1;
    
    $code_nouveau_support = $p1->code_support_activite;
    $status= Enregistrement_Seance_Activite::changer_support($p1->code_seance,
                                                             $code_nouveau_support);
    $this->assertTrue($status);
    $ok = $this->seance_a_comme_support($p1->code_seance, $code_nouveau_support);
    $this->assertTrue($ok);
    
    $code_nouveau_support = $p1->code_support_activite + 1;
    $status= Enregistrement_Seance_Activite::changer_support($p1->code_seance,
                                                             $code_nouveau_support);
    $this->assertTrue($status);
    $ok = $this->seance_a_comme_support($p1->code_seance, $code_nouveau_support);
    
  }
  
  public function testchangementSeance(): void {
    $jour = Calendrier::aujourdhui();
    $une_heure = new DateInterval('PT1H0M0S');
    $debut = $jour;
    $debut= $jour->add(new DateInterval('PT7H0M0S'));
    $fin = $debut->add($une_heure);
    
    $p1 = new Information_Participation_Seance_Activite();
    $p1->code_site = 1;
    $p1->code_support_activite = 1;
    $p1->code_participant = 999;
    $p1->debut = $debut->date_heure_sql();
    $p1->fin = $fin->date_heure_sql();
    
    Enregistrement_Seance_Activite::ajouter_participation($p1);
    $this->participations[] = $p1;
    $code_p1 = $p1->code_seance;
    
    // valeurs de code seance invalides : faut pas faire
    $valeur_invalide = 0;
    $valeur_possible = $code_p1;
    $ok = Enregistrement_Seance_Activite::changer_seance($valeur_invalide, $valeur_invalide);
    $this->assertFalse($ok);
    $ok = Enregistrement_Seance_Activite::changer_seance($valeur_invalide, $valeur_possible);
    $this->assertFalse($ok);
    $ok = Enregistrement_Seance_Activite::changer_seance($valeur_possible, $valeur_invalide);
    $this->assertFalse($ok);
    
    // une des deux seances n'existe pas
    $valeur_invalide = $this->code_derniere_seance() + 1;
    $valeur_possible =  $code_p1;
    
    $ok = Enregistrement_Seance_Activite::changer_seance($valeur_invalide, $valeur_invalide);
    $this->assertFalse($ok);
    $ok = Enregistrement_Seance_Activite::changer_seance($valeur_invalide, $valeur_invalide + 1);
    $this->assertFalse($ok);
    $ok = Enregistrement_Seance_Activite::changer_seance($valeur_invalide, $valeur_possible);
    $this->assertFalse($ok);
    $ok = Enregistrement_Seance_Activite::changer_seance($valeur_possible, $valeur_invalide);
    $this->assertFalse($ok);
    
    // nouveau code = code_actuel // ok, pas de changement (meme nombre de participation)
    $n1_avant = $this->compter_participations_seance($p1->code_seance);
    $ok = Enregistrement_Seance_Activite::changer_seance($code_p1, $code_p1);
    $this->assertTrue($ok);
    $n1_apres = $this->compter_participations_seance($p1->code_seance);
    $this->assertEquals($n1_avant, $n1_apres);
    
    // nouveau_code = code existant // ok, nombre de participations = somme des 2 seances
    $p2 = new Information_Participation_Seance_Activite();
    $p2->code_site = 1;
    $p2->code_support_activite = $p1->code_support_activite + 1;
    $p2->code_participant = $p1->code_participant + 1;
    $p2->debut = $debut->date_heure_sql();
    $p2->fin = $fin->date_heure_sql();
    Enregistrement_Seance_Activite::ajouter_participation($p2);
    $this->participations[] = $p2;
    
    $n1_avant = $this->compter_participations_seance($p1->code_seance);
    $n2_avant = $this->compter_participations_seance($p2->code_seance);
    $ok = Enregistrement_Seance_Activite::changer_seance($p1->code_seance, $p2->code_seance);
    $n1_apres = $this->compter_participations_seance($p1->code_seance);
    $n2_apres = $this->compter_participations_seance($p2->code_seance);
    $this->assertTrue($ok);
    $this->assertEquals(0, $n1_apres);
    $this->assertEquals($n2_avant + $n1_avant, $n2_apres);
    
    }
}
// ==========================================================================
?>
