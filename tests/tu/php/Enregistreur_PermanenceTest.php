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
 * description : Test unitaire de la classe Enregistreur_Permanence
 * utilisation : pphpunit --testdox <chemin_vers_ce_fichier_php>
 * dependances :
 * - enregistrement present dans la table de la base de donnees de test
 * ----------------------------------------------------------------------------
 * creation : 09-oct-2024 pchevaillier@gmail.com
 * revision :
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

// --- Classe sous test
require_once('php/enregistreur/enregistreur_permanence.php');

// --- autres classes
require_once('php/collecteur/collecteur_permanence.php');

// ============================================================================
/**
 * Test case.
 */
final class Enregistreur_PermanenceTest extends TestCase {
  
  private static ?PDO $bdd = null;

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
  
  private static function creer_donnees(): void {}
  
  private static function generer_donnees_test(): void {}
  
  public function testExtensionCalendrierPermanencesPourEquipe(): void {
    $codes_membres = [101,102, 103];
    $erreur = Enregistreur_Permanence::etend_calendrier($codes_membres); // methode sous test
    $this->assertEquals(0, $erreur);
  }
  
  public function testModificationResponsablePermanence(): void {
    
    // arguments avec valeurs valides
    $annee = 0;
    $semaine = 0;
    $code_nouveau_responsable = 0;
    $fait = Enregistreur_Permanence::change_responsable($semaine,
                                                       $annee,
                                                       $code_nouveau_responsable);
    $this->assertFalse($fait);

    // Enregistrement inexistant
    $annee = 2099;
    $semaine = 1;
    $code_nouveau_responsable = 101;
    $fait = Enregistreur_Permanence::change_responsable($semaine,
                                                       $annee,
                                                       $code_nouveau_responsable);
    $this->assertFalse($fait);

    // Enregistrement existant et arguments acec valeurs correctes
    $perm = Enregistrement_Permanence::recherche_derniere();
    $annee = $perm->annee();
    $semaine = $perm->semaine();
    $code_ancien_responsable = $perm->code_responsable(); // pour ensuite effacer l'effet de bord du test
    $code_nouveau_responsable = $code_ancien_responsable + 1;

    $fait = Enregistreur_Permanence::change_responsable($semaine,
                                                       $annee,
                                                       $code_nouveau_responsable);
    $this->assertTrue($fait);
    $perm = Enregistrement_Permanence::recherche_derniere();
    $this->assertEquals($code_nouveau_responsable, $perm->code_responsable());
    
    // Suppression effet de bord du test (si test ok)
    $fait = Enregistreur_Permanence::change_responsable($perm->semaine(),
                                                       $perm->annee(),
                                                       $code_ancien_responsable);
  }
  
  
  public function testPermutationResponsablesEntreDeuxPermanences(): void {
    
    // arguments avec valeurs valides
    $annee_1 = 0;
    $semaine_1 = 0;
    $annee_2 = 2024;
    $semaine_2 = 2;
    $fait = Enregistreur_Permanence::permute_responsable($semaine_1,
                                                       $annee_1,
                                                       $semaine_2,
                                                       $annee_2);
    $this->assertFalse($fait);
  
    // Deuxieme perm inexistante
    $annee_1 = 2099;
    $semaine_1 = 1;
    $annee_2 = 2024;
    $semaine_2 = 2;
    $code_resp2_avant = Enregistrement_Permanence::lire_code_responsable($semaine_2, $annee_2);
    
    $fait = Enregistreur_Permanence::permute_responsable($semaine_1,
                                                       $annee_1,
                                                       $semaine_2,
                                                       $annee_2);
    $this->assertFalse($fait);
    $code_resp2_apres = Enregistrement_Permanence::lire_code_responsable($semaine_2, $annee_2);
    $this->assertEquals($code_resp2_avant, $code_resp2_apres);
    
    // Deuxieme perm inexistante
    $annee_1 = 2024;
    $semaine_1 = 1;
    $annee_2 = 2099;
    $semaine_2 = 2;
    $code_resp1_avant = Enregistrement_Permanence::lire_code_responsable($semaine_1, $annee_1);
    
    $fait = Enregistreur_Permanence::permute_responsable($semaine_1,
                                                       $annee_1,
                                                       $semaine_2,
                                                       $annee_2);
    $this->assertFalse($fait);
    $code_resp1_apres = Enregistrement_Permanence::lire_code_responsable($semaine_1, $annee_1);
    $this->assertEquals($code_resp1_avant, $code_resp1_apres);
    
    // arguments avec valeurs valides
    $annee_1 = 2024;
    $semaine_1 = 1;
    $annee_2 = 2024;
    $semaine_2 = 2 ;
    
    $code_resp1_avant = Enregistrement_Permanence::lire_code_responsable($semaine_1, $annee_1);
    $code_resp2_avant = Enregistrement_Permanence::lire_code_responsable($semaine_2, $annee_2);

    $fait = Enregistreur_Permanence::permute_responsable($semaine_1,
                                                       $annee_1,
                                                       $semaine_2,
                                                       $annee_2);
    $this->assertTrue($fait);
    $code_resp1_apres = Enregistrement_Permanence::lire_code_responsable($semaine_1, $annee_1);
    $code_resp2_apres = Enregistrement_Permanence::lire_code_responsable($semaine_2, $annee_2);
    $this->assertEquals($code_resp1_apres, $code_resp2_avant);
    $this->assertEquals($code_resp2_apres, $code_resp1_avant);
  }
}
// ============================================================================
?>
