<?php
// ==========================================================================
// contexte : Resabel - systeme de REServAtion de Bateau En Ligne
//            Tests fonctionnels unitaires
// description : Test unitaire de la classe Enregistrement_Seance_Activite
// copyright (c) 2023 AMP. Tous droits reserves.
// --------------------------------------------------------------------------
// utilisation : phpunit --testdox <chemin_vers_ce_fichier_php>
// dependances : base de donnees
// utilise avec :
//  - depuis 2023 :
//    PHP 8.2 et PHPUnit 9.5 sur macOS 13.2 ;
// --------------------------------------------------------------------------
// creation : 07-fev-2023 pchevaillier@gmail.com
// revision : 17-feb-2023 pchevaillier@gmail.com + changer_horaire
// --------------------------------------------------------------------------
// commentaires :
// - en construction.
// attention :
// -
// a faire :
// - a completer
// - collecter participants creneau horaire (couverture + fonctionnalite)
// - collecter (nombreuses options > enriente couverture du code)
// ==========================================================================
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

// --------------------------------------------------------------------------
// --- Classes de l'enviromment de test
include_once './base_donnees.php';

// --- Classes de l'application
set_include_path('./../../../');

require_once('php/bdd/enregistrement_seance_activite.php');

// ==========================================================================
/**
 * Test case.
 */
class Enregistrement_Seance_activiteTest extends TestCase {

  private $enregistrement;
  private $participations = array();

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

  private function compter_seance(int $code_seance): int {
    $resultat = -1;
    $source = PREFIX_TABLE . "seances_activite";
    try {
      $bdd = base_donnees::acces();
      $requete = "SELECT COUNT(*) AS n FROM " . $source
        . " WHERE code = " . $code_seance;
      $resultat = $bdd->query($requete);
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
  
  private function seance_sans_responsable(int $code_seance): bool {
    $condition = false;
    $source = PREFIX_TABLE . "seances_activite";
    try {
      $bdd = base_donnees::acces();
      $requete = "SELECT code_responsable FROM " . $source
        . " WHERE code = " . $code_seance;
      $resultat = $bdd->query($requete);
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
      $bdd = base_donnees::acces();
      $requete = "SELECT COUNT(*) AS n FROM " . $source
        . " WHERE code_seance = " . $code_seance;
      $resultat = $bdd->query($requete);
      $donnee = $resultat->fetch(PDO::FETCH_OBJ);
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

  /*
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
    $p2->debut = $debut->date_heure_sql();
    $p2->fin = $fin->date_heure_sql();
    $p2->code_seance = $p1->code_seance; // pour qu'il s'agisse de participations a la meme seance
    
    Enregistrement_Seance_Activite::ajouter_participation($p2);
    $nombre_effectif = Enregistrement_Seance_Activite::compter_participations($p2);
    $this->assertEquals(1, $nombre_effectif, "enregistrement participation activite");
  }
  */
  /*
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
    
    $p2 = new Information_Participation_Seance_Activite();
    $p1->code_site = 1;
    $p1->code_support_activite = 1;
    $p1->code_participant = 888;
    $p2->debut = $debut->date_heure_sql();
    $p2->fin = $fin->date_heure_sql();
    
    Enregistrement_Seance_Activite::ajouter_participation($p1);
    $p2->code_seance = $p1->code_seance;
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
  */
  /*
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
  */
  /*
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
    print("seance: " . $p1->code_seance . PHP_EOL);
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
    print("seance: " . $p2->code_seance . PHP_EOL);
    $this->participations[] = $p2;
    $np_apres = $this->compter_participations_seance($p2->code_seance);
    $ns_apres = $this->compter_seance($p2->code_seance);
    $this->assertEquals(2, $np_apres, "enregistrements participation activite");
    $this->assertEquals(1, $ns_apres, "enregistrement seance activite");
    $ok = $this->seance_sans_responsable($p2->code_seance);
    $this->assertTrue($ok, "seance sans responsable");
   
    // tentative d'ajouter deux fois la meme participation
    $status = Enregistrement_Seance_Activite::ajouter_participation($p2);
    $this->assertEquals(6, $status);
    $np_apres = $this->compter_participations_seance($p2->code_seance);
    $this->assertEquals(2, $np_apres, "enregistrements participation activite");
  }
   */
  /*
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
  */
  /*
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
    $p2->code_seance = $p1->code_seance;
    Enregistrement_Seance_Activite::ajouter_participation($p2);
    $status = Enregistrement_Seance_Activite::supprimer_participation($p2);
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
   */
  /*
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
   */
}
// ==========================================================================
?>
