<?php
// ==========================================================================
// contexte : Resabel - systeme de REServAtion de Bateau En Ligne
//            Tests unitaires
// description : Test unitaire de la classe Site_Activite (abstraite)
//               et de ses specialisations
// copyright (c) 2023 AMP. Tous droits reserves.
// --------------------------------------------------------------------------
// utilisation : phpunit --testdox <chemin_vers_ce_fichier_php>
// dependances :
// utilise avec :
//  - depuis 2023 :
//    PHP 8.2 et PHPUnit 9.5 sur macOS 13.2 ;
// --------------------------------------------------------------------------
// creation : 18-dec-2023 pchevaillier@gmail.com
// revision :
// --------------------------------------------------------------------------
// commentaires :
// - partiel
// attention :
// - la classe Site_activite est abstraite
// a faire :
// -
// ==========================================================================
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

set_include_path('./../../..');

require_once 'php/metier/site_activite.php';
//require_once 'PHPUnit/Autoload.php';

/**
 * Instant test case.
 */
final class Site_ActiviteTest extends TestCase {

  /**
   * Tests
   */
  public function testConstructeur(): void {
    $code = 1;
    $site = new Site_Activite_Mer($code); // une des specialisations instantiables
    $this->assertEquals($code, $site->code());
  }
  
  public function testDefinirCode(): void {
    $dummy = 1;
    $site = new Site_Activite_Mer($dummy); // une des specialisations instantiables
    $code = 2;
    $site->def_code($code);
    $this->assertEquals($code, $site->code());
  }

}
// ===========================================================================
?>
