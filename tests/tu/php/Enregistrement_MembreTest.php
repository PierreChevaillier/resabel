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
 * description : Test unitaire de la classe Enregitrement_Membre
 * utilisation : (shell) phpunit --testdox <chemin_vers_ce_fichier_php>
 * dependances :
 * - presence enregistrements dans les tables de test
 * utilise avec :
 * - PHP 8.2 et PHPUnit 9.5
 * ----------------------------------------------------------------------------
 * creation : 20-fev-2023 pchevaillier@gmail.com
 * revision : 16-sep-2024 pchevaillier@gmail.com + test valeurs lues
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

// --------------------------------------------------------------------------
// --- Classes de l'enviromment de test
include_once './base_donnees.php';

// --- Classes de l'application
set_include_path('./../../../');

include_once('php/utilitaires/definir_locale.php');
require_once('php/bdd/enregistrement_membre.php');
require_once('php/metier/membre.php');

// ==========================================================================
/**
 * Test case.
 */
class Enregistrement_MembreTest extends TestCase {

  private static ?PDO $bdd = null;
  
  private int $code_composante_resabel = 2;
  private int $code_role_admin = 8;
  
  private int $code_admin_resabel = 101;
  private int $code_cdb = 20;
  private int $code_equipier = 19007;
  private int $code_visiteur = 9021;
  private int $code_lambda = 9999;
  
  private $personae = array();
  
  private Enregistrement_Membre $enreg;
  
  private Membre $membre;
  
  public static function setUpBeforeClass(): void {
    print("Connection to the database" . PHP_EOL);
    self::$bdd = Base_Donnees::acces();
  }

  public static function tearDownAfterClass(): void {
    print("Delete connection handler" . PHP_EOL);
    self::$bdd = null;
  }
  
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
  
  private function initialiser_membre(): void {
    $this->membre = new membre($this->code_lambda);
    $this->membre->genre = "F";
    $this->membre->prenom = "Hélène";
    $this->membre->nom = "Créac'h";
    $this->membre->date_naissance = new Instant("2004-12-23");
    $this->membre->code_commune = 29190;
    $this->membre->rue = "Françoise Fañch";
    $this->membre->telephone = "0605040302";
    $this->membre->telephone2 = "9805040302";
    $this->membre->courriel = "duduche@mail.bzh";
    
    $this->membre->def_chef_de_bord(1);
    $this->membre->niveau = 2;
    $this->membre->type_licence = "B";
    $this->membre->num_licence = "29200";
    
    $this->membre->def_identifiant("helene.creach");
    $this->membre->def_actif(1);
    $this->membre->def_autorise_connecter(1);
  }
  
  private static function supprimer_enregistrement_membre(int $code_membre): bool {
    self::$bdd->beginTransaction();
    
    // d'abord dans la table des connexions car table fille
    $code_sql = "DELETE FROM " . Enregistrement_Connexion::source()
    . " WHERE code_membre = " . $code_membre;
    $n = self::$bdd->exec($code_sql);
    $ok = ($n == 1);
    
    // ensuite dans la table des membres car table mere
    if ($ok) {
      $code_sql = "DELETE FROM " . Enregistrement_Membre::source()
      . " WHERE code = " . $code_membre;
      $n = self::$bdd->exec($code_sql);
      $ok = ($n == 1);
    }
    self::$bdd->commit();
    return $ok;
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
    $this->initialiser_membre();
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
  public function testSourceTableMembre(): void {
    $this->assertEquals(PREFIX_TABLE . 'membres', Enregistrement_Membre::source());
  }

  public function testGenererNouveauCodeMembre() : void {
    $nouveau_code = Enregistrement_Membre::generer_nouveau_code(); // methode sous test
    
    // verifications : le code genere n'existe pas
    $this->assertTrue($nouveau_code > 0);
    $code_sql = 'SELECT code FROM '  . Enregistrement_Membre::source()
      . ' WHERE code = ' . $nouveau_code;
    $resultat = self::$bdd->query($code_sql);
    $n = $resultat->fetch(PDO::FETCH_OBJ);
    $this->assertFalse($n);
    $resultat->closeCursor();
  }
  
  public function testGenererMotDePasse() {
    $mdp = "";
    $mdp = Enregistrement_Membre::generer_mot_passe();
    $this->assertTrue(password_verify('motdepassebidon', $mdp));
  }
  
  public function testFormatterTelephoneTable() {
    $numero_attendu = '060504030201';
    
    $numero = $numero_attendu;
    $numero_formatte = Enregistrement_Membre::formatter_telephone_table($numero);
    $this->assertEquals($numero_attendu, $numero_formatte);
    
    $numero = ' 06 05.04/03.02-01  ';
    $numero_formatte = Enregistrement_Membre::formatter_telephone_table($numero);
    $this->assertEquals($numero_attendu, $numero_formatte);
  }
  
  /**
   * Teste si une personne a le role administrateurice pour resabel
   */
  public function testRechercheSiPersonneEstAdminResabel(): void {
    $this->enreg->def_membre($this->personae[$this->code_admin_resabel]);
    $condition = $this->enreg->recherche_si_admin();
    $this->assertTrue($condition);
    $this->enreg->def_membre($this->personae[$this->code_equipier]);
    $condition = $this->enreg->recherche_si_admin();
    $this->assertFalse($condition);
  }
  
  public function testLire(): void {
    $code = $this->membre->code();
    self::supprimer_enregistrement_membre($code); // au cas ou...
    
    $this->enreg->def_membre($this->membre);
    $ok = $this->enreg->ajouter();
    
    if ($ok) {
      $membre = new Membre($code);
      $this->enreg->def_membre($membre);
      $resultat = null;
      $resultat = $this->enreg->lire(); // appel methode sous test
      
      $this->assertTrue($resultat);
      $this->assertEquals($this->membre->code(), $membre->code());
      $this->assertEquals($this->membre->genre, $membre->genre);
      $this->assertEquals($this->membre->prenom, $membre->prenom);
      $this->assertEquals($this->membre->nom, $membre->nom);
      $this->assertEquals($this->membre->code_commune, $membre->code_commune);
      $this->assertEquals($this->membre->rue, $membre->rue);
      $this->assertEquals($this->membre->telephone, $membre->telephone);
      $this->assertEquals($this->membre->telephone2, $membre->telephone2);
      $this->assertEquals($this->membre->courriel, $membre->courriel);
      
      $this->assertTrue($membre->date_naissance->est_egal($this->membre->date_naissance));
      $this->assertEquals($this->membre->est_chef_de_bord(), $membre->est_chef_de_bord());
      $this->assertEquals($this->membre->niveau, $membre->niveau);
      //$this->assertEquals($this->membre->type_licence, $membre->type_licence);
      $this->assertEquals($this->membre->num_licence, $membre->num_licence);
      
      $this->assertEquals($this->membre->identifiant(), $membre->identifiant());
      $this->assertEquals($this->membre->est_actif(), $membre->est_actif());
      $this->assertEquals($this->membre->est_autorise_connecter(), $membre->est_autorise_connecter());
    }
    
    self::supprimer_enregistrement_membre($code);
  }
  
  public function testModifier(): void {
    $code = $this->code_admin_resabel;
    $membre = new Membre($code);
    $this->enreg->def_membre($membre);
    
    // Modification de la valeur de certains attributs (dans les tables membres et connexions
    // ici par simplicite : on inverse des valeurs
    $this->enreg->lire(); // suppose que cette methode est conrrectement implementee

    $actif = $membre->est_actif() ? 0: 1; // attention : entier et inverse
    $cdb = $membre->est_chef_de_bord() ? 0: 1; // idem
    $membre->def_actif($actif);
    $membre->def_chef_de_bord($cdb);
    
    $modif_ok = $this->enreg->modifier(); // appel de la methode sous test
    
    // verifications
    $this->assertTrue($modif_ok);
    $this->enreg->lire();
    $this->assertEquals($actif, $membre->est_actif());
    $this->assertEquals($cdb, $membre->est_chef_de_bord());
    
    // remet les choses comme elles etaient avant le test
    $actif = $membre->est_actif() ? 0: 1; // attention : entier et inverse
    $cdb = $membre->est_chef_de_bord() ? 0: 1;
    $membre->def_actif($actif);
    $membre->def_chef_de_bord($cdb);
    $modif_ok = $this->enreg->modifier();
    $this->assertTrue($modif_ok);
    
    // verifications
    $this->enreg->lire();
    $this->assertEquals($actif, $membre->est_actif());
    $this->assertEquals($cdb, $membre->est_chef_de_bord());
    
  }
  
  public function testModifierStatutChefDeBord(): void {
    $code = $this->code_cdb;
    $membre = new Membre($code);
    $this->enreg->def_membre($membre);
    
    $this->enreg->lire();
    
    $statut_actuel = $membre->est_chef_de_bord();
    $nouveau_statut = $statut_actuel ? 0: 1; // inversion du status
    $apres_modification = ! $statut_actuel;
    
    $this->enreg->modifier_cdb($nouveau_statut); // appel de la methode sous test
    
    // Verifications
    //printf("\n Avant : %s \n", $statut_actuel ? "True" : "False");
    //printf("Apres : %s \n", $apres_modification? "True" : "False");
    $msg = "(1er changement) Passage de" . ($statut_actuel? "": " pas") .  " chef de bord a "
      . ($apres_modification? "": "pas ") . "chef de bord";
    $this->assertEquals($apres_modification, $membre->est_chef_de_bord(), $msg);
    
    // remet les choses comme elles etaient avant le test et test à nouveau
    $statut_actuel = $membre->est_chef_de_bord();
    $nouveau_statut = $statut_actuel ? 0: 1; // inversion du status
    $apres_modification = ! $statut_actuel;
    
    $this->enreg->modifier_cdb($nouveau_statut);
    
    $msg = "(2e changement) Passage de" . ($statut_actuel? "": " pas") .  " chef de bord a "
      . ($apres_modification? "": "pas") . " chef de bord";
    $this->assertEquals($apres_modification, $membre->est_chef_de_bord(),  $msg);
  }
  
  public function testAjouter(): void {
    $code = $this->code_lambda;
    
    // cas ou l'enregistreur n'est pas associe a un membre
    $membre = new Membre($code);
    $enreg = new Enregistrement_Membre();
    $this->assertFalse($enreg->ajouter());  // appel de la methode sous test
    
    // enregistreur avec un membre bien initialise
    $enreg->def_membre($this->membre);
  
    $status = $enreg->ajouter(); // appel de la methode sous test
    $this->assertTrue($status);
    // la verification des valeurs de champs est faire dans testLire()

    // Suppression des effets de bord du test
    $ok = self::supprimer_enregistrement_membre($code);
    // si on peut supprimer l'enregistrement, c'est qu'il a ete ajoute
    $this->assertTrue($ok);
  }
  
  public function testCollecter(): void {
    $personnes = null;
    $criteres = array();
    $status = Enregistrement_Membre::collecter($criteres, "", "", $personnes);
    $this->assertTrue($status);
    $this->assertTrue(count($personnes) > 0);
  }
  
}
// ==========================================================================
?>
