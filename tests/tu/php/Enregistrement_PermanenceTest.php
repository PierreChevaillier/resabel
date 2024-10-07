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
 * description : Tests fonctionnels unitaires de la classe Enregistrement_Permanence
 * utilisation : phpunit --testdox <chemin_vers_ce_fichier_php>
 * dependances :
 * - structure table
 * utilise avec :
 * - PHP 8.2 et PHPUnit 9.5 sur macOS 13.6
 * ----------------------------------------------------------------------------
 * creation : 05-oct-2024 pchevaillier@gmail.com
 * revision :
 * ----------------------------------------------------------------------------
 * commentaires :
 * - en evolution
 * attention :
 * -
 * a faire :
 * - a completer
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
require_once('php/bdd/enregistrement_permanence.php');

// autres classes
require_once 'php/metier/calendrier.php';
require_once 'php/metier/permanence.php';

// ============================================================================
/**
 * Test case.
 */
class Enregistrement_PermanenceTest extends TestCase {

  private static ?PDO $bdd = null;
  private static $nom_table = "permanences";
  private $enregistrement;
  private $permanences = array();

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
    $this->supprimer_permanences();
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown(): void {
    foreach ($this->permanences as $perm) {
      $this->supprimer_permanences($perm);
    }
    parent::tearDown();
  }

  private function supprimer_permanences(): void {
    $source = PREFIX_TABLE . self::$nom_table;
    $requete = self::$bdd->prepare("DELETE FROM " . $source . " WHERE annee = :annee AND semaine = :semaine");
    
    foreach ($this->permanences as $perm) {
      try {
        $annee = $perm->annee();
        $semaine = $perm->semaine();
        $requete->bindParam(':annee', $annee, PDO::PARAM_INT);
        $requete->bindParam(':semaine', $semaine, PDO::PARAM_INT);
        $requete->execute();
      } catch (PDOexception $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
    }
    return;
  }
  
  /**
   * Teste le nom de la table source des informations
   */
  public function testSourceTablePermanance(): void {
    $this->assertEquals(PREFIX_TABLE . self::$nom_table, Enregistrement_Permanence::source());
  }

  /**
   * Test a faire AVANT testCollectePermanencesFutures
   */
  public function testLireDernierePermanenceEnregistree(): void {
    $perm = Enregistrement_Permanence::recherche_derniere();
    $this->assertNotNull($perm);
    echo PHP_EOL, "derniere permanence enregistree :",
      " annee = " , $perm->annee(),
      " semaine = " , $perm->semaine(),
    PHP_EOL;
  }
  
  /**
   * Recherche des eventuelles parmanences posterieures a une permanence donnee
   */
  public function testCollectePermanencesFutures(): void {
 
    $derniere = Enregistrement_Permanence::recherche_derniere();
    $futures = array();
    
    $annee = 0;
    $semaine = 0;
    // cas ou il n'y a pas de permanence enregistrees apres la permanence
    if (! is_null($derniere))
      $annee = $derniere->annee() + 1;
    else
      $annee = 1970;
    $semaine = 1;
    $perm = new Permanence($semaine, $annee);
    
    $this->enregistrement = new Enregistrement_Permanence();
    $this->enregistrement->def_permanence($perm);
    
    $this->enregistrement->collecter_futures($futures); // methode sous test
    $this->assertEquals(0, count($futures));
    
    // cas d'une permanence dans l'annee civile courante
    if (! is_null($derniere)) {
      $annee = $derniere->annee();
      $semaine = max(1, $derniere->semaine() - 1);
      $perm = new Permanence($semaine, $annee);
    
      echo PHP_EOL, "permanence reference :",
        " annee = " , $perm->annee(),
        " semaine = " , $perm->semaine(),
      PHP_EOL;
      
      $this->enregistrement = new Enregistrement_Permanence();
      $this->enregistrement->def_permanence($perm);
    
      $futures = array();
      $this->enregistrement->collecter_futures($futures);
      $n = count($futures);
      $this->assertEquals(2, $n);
      $this->assertEquals($annee, $futures[0]->annee());
      $this->assertEquals($semaine, $futures[0]->semaine());
      $this->assertEquals($derniere->annee(), $futures[1]->annee());
      $this->assertEquals($derniere->semaine(), $futures[1]->semaine());
    }
    
    // cas d'une permanence avant l'annee civile courante
    // ATTENTION : suppose que la donnee existe
    if (! is_null($derniere)) {
      $annee = $derniere->annee() - 1;
      $semaine = max(1, $derniere->semaine() - 1);
      $perm = new Permanence($semaine, $annee);
    
      /*
      echo PHP_EOL, "permanence reference :",
        " annee = " , $perm->annee(),
        " semaine = " , $perm->semaine(),
      PHP_EOL;
      */
      
      $this->enregistrement = new Enregistrement_Permanence();
      $this->enregistrement->def_permanence($perm);
    
      $futures = array();
      $this->enregistrement->collecter_futures($futures); // methode sous test
      $nb_futures = count($futures);
      
      $dernier_jour = new Instant($annee . '-12-31 23:59:00');
      $num_semaine_dernier_jour = $dernier_jour->numero_semaine();
      
      //echo PHP_EOL, "derniere semaine de l'annee precedente :", $num_semaine_dernier_jour, PHP_EOL;
      $n = $num_semaine_dernier_jour - $semaine + $derniere->semaine() + 1;
      $this->assertEquals($n, $nb_futures); // premiere verification a faire
      $this->assertEquals($annee, $futures[0]->annee());
      $this->assertEquals($semaine, $futures[0]->semaine());
      $this->assertEquals($derniere->annee(), $futures[$nb_futures - 1]->annee());
      $this->assertEquals($derniere->semaine(), $futures[$nb_futures - 1]->semaine());
      $n1 = $semaine - 1;
      $n2 = 0;
      foreach ($futures as $p) {
        if ($p->annee() == $annee) {
          $n1 = $n1 + 1;
          $this->assertEquals($n1, $p->semaine());
        } elseif ($p->annee() == $derniere->annee()) {
          $n2 = $n2 + 1;
          $this->assertEquals($n2, $p->semaine());
        }
      }
    }
  }
  
  /**
   * Evaluation de l'identite du responsable d'une permanence
   */
  public function testPermanenceACommeResponsable(): void {
    $perm = Enregistrement_Permanence::recherche_derniere();
    if (! is_null($perm)) {
      $this->enregistrement = new Enregistrement_Permanence();
      $this->enregistrement->def_permanence($perm);
      
      $code_membre = $perm->code_responsable();
      $personne = new Personne($code_membre);
      
      $valeur = $this->enregistrement->a_comme_responsable($personne);
      $this->assertTrue($valeur);
      
      $code_membre = $code_membre + 1;
      $autre_personne = new Personne($code_membre);
      
      $valeur = $this->enregistrement->a_comme_responsable($autre_personne);
      $this->assertFalse($valeur);
    }
  }
  
  public function testChangementResponsable(): void {
    $perm = Enregistrement_Permanence::recherche_derniere();
    if (! is_null($perm)) {
      $code_membre_avant = $perm->code_responsable();
      
      $this->enregistrement = new Enregistrement_Permanence();
      $this->enregistrement->def_permanence($perm);
      
      $fait = $this->enregistrement->change_responsable($code_membre);
      $this->assertTrue($fait);
      $this->assertEquals($code_membre,  $this->enregistrement->permanence()->code_responsable());
      
      
    }
  }
}
// ==========================================================================
?>
