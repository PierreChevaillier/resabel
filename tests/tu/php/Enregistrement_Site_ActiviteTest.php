<?php
// ==========================================================================
// contexte : Resabel - systeme de REServAtion de Bateau En Ligne
//            Tests unitaires
// description : Test unitaire de la classe Enregitrement_Site_Activite
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
// -
// a faire :
// -
// ==========================================================================
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

// --------------------------------------------------------------------------
// --- Classes de l'enviromment de test
include_once './base_donnees.php';

// --- Classes de l'application
set_include_path('./../../../');

require_once('php/bdd/enregistrement_site_activite.php');

// ==========================================================================
/**
 * Test case.
 */
class Enregistrement_Site_ActiviteTest extends TestCase {

  /**
   * Teste le nom de la table spource des informations
   */
  public function testSourceTableSiteActivite(): void {
    $this->assertEquals(PREFIX_TABLE . 'sites_activite', Enregistrement_Site_Activite::source());
  }
  
  public function testCollecterUnSite(): void {
    $site = null;
    $code_site = 1;

    $sites = array();
    $criteres = ' site.code = ' . $code_site;
    $ordre = "";
    $ok = Enregistrement_Site_Activite::collecter($criteres,
                                                  $ordre,
                                                  $sites);
    $this->assertTrue($ok);
    $this->assertEquals(1, count($sites));
  
    foreach ($sites as $sa)
      $site = $sa;
    $this->assertFalse(is_null($site));
    
    $this->assertEquals($code_site, $site->code());
  }
}
// ==========================================================================
?>
