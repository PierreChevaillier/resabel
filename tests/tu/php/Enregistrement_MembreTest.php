<?php
// ==========================================================================
// contexte : Resabel - systeme de REServAtion de Bateau En Ligne
//            Tests unitaires
// description : Test unitaire de la classe Enregitrement_Membre
// copyright (c) 2023 AMP. Tous droits reserves.
// --------------------------------------------------------------------------
// utilisation : phpunit --testdox <chemin_vers_ce_fichier_php>
// dependances :
// utilise avec :
//  - depuis 2023 :
//    PHP 8.2 et PHPUnit 9.5 sur macOS 13.2 ;
// --------------------------------------------------------------------------
// creation : 20-fev-2023 pchevaillier@gmail.com
// revision : 30-nov-2023 pchevaillier@gmail.com
// --------------------------------------------------------------------------
// commentaires :
// - debut
// attention :
//  - incomplet
//  - donnees de test lues dans la base de donnees
// a faire :
// - beaucoup de choses
// ==========================================================================
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

// --------------------------------------------------------------------------
// --- Classes de l'enviromment de test
include_once './base_donnees.php';

// --- Classes de l'application
set_include_path('./../../../');

include_once('php/utilitaires/definir_locale.php');
require_once('php/bdd/enregistrement_membre.php');

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
  
  private $personae = array();
  
  private Enregistrement_Membre $enreg;
  
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
    $code = $this->code_admin_resabel;
    $this->enreg->def_membre($this->personae[$code]);
    $resultat = null;
    $resultat = $this->enreg->lire();
    $this->assertTrue($resultat);
    // TODO: tester les valeurs lues
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
    $code = 99999;
    $membre = new Membre($code);
    $enreg = new Enregistrement_Membre();
    
    // cas ou l'enregistreur n'est pas associe a un membre
    $this->assertFalse($enreg->ajouter());  // appel de la methode sous test
    
    $enreg->def_membre($membre);
    // informations sur le membre
    $membre->genre = "F";
    $membre->prenom = "Hélène";
    $membre->nom = "Créac'h";
    
    $membre->code_commune = 29190;
    $membre->rue = "Françoise Fañch";
    $membre->telephone = "0605040302";
    $membre->telephone2 = "9805040302";
    $membre->courriel = "duduche@mail.bzh";
    
    $membre->def_chef_de_bord(0);
    $membre->niveau = 2;
    $membre->type_licence = "B";
    $membre->num_licence = "29200";
    
    $membre->def_identifiant("helene.creach");
    $membre->def_actif(1);
    $membre->def_autorise_connecter(0);
  
    $status = $enreg->ajouter(); // appel de la methode sous test
    $this->assertTrue($status);
    // TODO: verfier les valeurs de champs

    // Suppression des effets de bord du test :
    // suppression des 2 enregistrements ajoutes
    
    self::$bdd->beginTransaction();
    
    // d'abord dans la table des connexions car table fille
    $code_sql = "DELETE FROM " . Enregistrement_Connexion::source()
    . " WHERE code_membre = " . $code;
    $n = self::$bdd->exec($code_sql);
    $this->assertEquals(1, $n); // si on peut le detruire c'est qu'il a ete ajoute
    
    // ensuite dans la table des membres car table mere
    $code_sql = "DELETE FROM " . Enregistrement_Membre::source()
    . " WHERE code = " . $code;
    $n = self::$bdd->exec($code_sql);
    $this->assertEquals(1, $n); // si on peut le detruire c'est qu'il a ete ajoute

    self::$bdd->commit();
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
