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
 * description : Tests fonctionnels unitaires de la classe Regime_Ouverture
 * utilisation : phpunit --testdox <chemin_vers_ce_fichier_php>
 * dependances :
 * - 
 * utilise avec :
 * - PHP 8.2 et PHPUnit 9.5 sur macOS 13.6
 * ----------------------------------------------------------------------------
 * creation : 19-mar-2025 pchevaillier@gmail.com
 * revision : 25-oct-2025 pchevaillier@gmail.com + differentes configs.
 * revision : 28-oct-2025 pchevaillier@gmail.com + regime hebdomadaire
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

set_include_path('./../../..');
include_once('php/utilitaires/definir_locale.php');

require_once 'php/metier/calendrier.php';
require_once 'php/metier/regime_ouverture.php';

final class Regime_OuvertureTest extends TestCase {

  public array $creneaux = [];
  
  private function afficher_creneaux(): void {
    $heure_debut = $this->creneaux[0]->debut()->heure_texte();
    $heure_fin = $this->creneaux[0]->fin()->heure_texte();
    print(PHP_EOL . "premier creneau : de " . $heure_debut . ' à ' . $heure_fin . PHP_EOL);
    $nb_creneaux = count($this->creneaux);
    print("nombre creneau(x) : " . $nb_creneaux . PHP_EOL);
    $heure_debut = $this->creneaux[$nb_creneaux - 1]->debut()->heure_texte();
    $heure_fin = $this->creneaux[$nb_creneaux - 1]->fin()->heure_texte();
    print("dernier creneau : de " . $heure_debut . ' à ' . $heure_fin . PHP_EOL);
  }
  
  public function testDefinirCreneauxRegimeDiurne(): void {
    
    $code_regime = 1;
    $regime = new Regime_Diurne($code_regime);
    
    // Parametrage du regime d'ouverture (diurne)
    $regime->heure_ouverture = new DateInterval('PT9H0M0S');
    $regime->heure_fermeture = new DateInterval('PT21H0M0S');
    $regime->tolerance_lever_soleil = new DateInterval('PT0H0M0S');
    $regime->tolerance_coucher_soleil = new DateInterval('PT0H0M0S');
    
    //echo ' Lever ', $lever->format('d-m-Y H:i:s')  . PHP_EOL;
    $jour = new Instant("2025-03-30 00:00:00");
    $latitude = 48.3489;
    $longitude = -4.68248;
    $info_soleil = date_sun_info($jour->getTimestamp(),
                                 $latitude,
                                 $longitude);
    
    $t_lever = $info_soleil['sunrise'];
    $lever = Calendrier::creer_Instant($t_lever);
    print(' Lever : ' . $lever->format('d-m-Y H:i:s') . PHP_EOL);

    $t_coucher = $info_soleil['sunset'];
    $coucher = Calendrier::creer_Instant($t_coucher);
    print('Coucher ' . $coucher->format('d-m-Y H:i:s')  . PHP_EOL);
    
    $this->creneaux = $regime->definir_creneaux($jour, $latitude, $longitude);
    $this->assertEquals('09:00', $this->creneaux[0]->debut()->heure_texte());
    $n = count($this->creneaux);
    $this->assertEquals('20:00', $this->creneaux[$n-1]->fin()->heure_texte());
    $this->afficher_creneaux();
    
    // ajout tolerance coucher soleil
    $regime->tolerance_coucher_soleil = new DateInterval('PT0H20M0S');
    $this->creneaux = $regime->definir_creneaux($jour, $latitude, $longitude);
    $this->assertEquals('09:00', $this->creneaux[0]->debut()->heure_texte());
    $n = count($this->creneaux);
    $this->assertEquals('21:00', $this->creneaux[$n-1]->fin()->heure_texte());
    
    // ajout tolerance lever du soleil
    
    $regime->tolerance_lever_soleil = new DateInterval('PT0H20M0S');
    $this->creneaux = $regime->definir_creneaux($jour, $latitude, $longitude);
    $this->assertEquals('09:00', $this->creneaux[0]->debut()->heure_texte());
    $n = count($this->creneaux);
    $this->assertEquals('21:00', $this->creneaux[$n-1]->fin()->heure_texte());

    $regime->heure_ouverture = new DateInterval('PT7H0M0S');
    $regime->tolerance_lever_soleil = new DateInterval('PT1H00M0S');
    $this->creneaux = $regime->definir_creneaux($jour, $latitude, $longitude);
    $this->assertEquals('07:00', $this->creneaux[0]->debut()->heure_texte());
    $n = count($this->creneaux);
    $this->assertEquals('21:00', $this->creneaux[$n-1]->fin()->heure_texte());
    
    // heures d'ouverture et fermeture de nuit
    $regime->heure_ouverture = new DateInterval('PT7H0M0S');
    $regime->heure_fermeture = new DateInterval('PT22H0M0S');
    $regime->tolerance_lever_soleil = new DateInterval('PT0H0M0S');
    $regime->tolerance_coucher_soleil = new DateInterval('PT0H0M0S');
    
    $this->creneaux = $regime->definir_creneaux($jour, $latitude, $longitude);
    $this->assertEquals('08:00', $this->creneaux[0]->debut()->heure_texte());
    $n = count($this->creneaux);
    $this->assertEquals('20:00', $this->creneaux[$n-1]->fin()->heure_texte());

  }
  
  public function testDefinirCreneauxRegimeDiurneSansContrainteJour(): void {
    
    $jour = new Instant("2025-03-30 00:00:00");
    
    // Parametrage du regime d'ouverture
    $code_regime = 1;
    $regime = new Regime_Diurne($code_regime);
    $regime->jour_uniquement = false;
    $regime->heure_ouverture = new DateInterval('PT7H0M0S');
    $regime->heure_fermeture = new DateInterval('PT22H0M0S');
    
    $this->creneaux = $regime->definir_creneaux($jour); // methode sous test
    
    $this->assertEquals('07:00', $this->creneaux[0]->debut()->heure_texte());
    $n = count($this->creneaux);
    $this->assertEquals('22:00', $this->creneaux[$n-1]->fin()->heure_texte());
    $this->afficher_creneaux();
  }
  
  public function testDefinirCreaneauxRegimeJournalierHeureEte(): void {
    $code_regime = 1;
    $regime = new Regime_Journalier($code_regime);
    $regime->heure_ouverture = new DateInterval('PT9H0M0S');
    $regime->heure_fermeture = new DateInterval('PT21H0M0S');
    $regime->decalage_heure_hiver = new DateInterval('PT0H15M0S');
    
    $jour = new Instant('2026-06-01');
    $this->creneaux = $regime->definir_creneaux($jour); // methode sous test
    
    $this->assertEquals('09:00', $this->creneaux[0]->debut()->heure_texte());
    $n = count($this->creneaux);
    $this->assertEquals('21:00', $this->creneaux[$n-1]->fin()->heure_texte());
    $this->afficher_creneaux();
  }
  
  public function testDefinirCreaneauxRegimeJournalierHeureHiver(): void {
    $code_regime = 1;
    
    $regime = new Regime_Journalier($code_regime);
    $regime->heure_ouverture = new DateInterval('PT9H0M0S');
    $regime->heure_fermeture = new DateInterval('PT21H0M0S');
    $regime->decalage_heure_hiver = new DateInterval('PT0H15M0S');
    
    $jour = new Instant('2026-12-01');
    $this->creneaux = $regime->definir_creneaux($jour); // methode sous test
    
    $this->assertEquals('09:15', $this->creneaux[0]->debut()->heure_texte());
    $n = count($this->creneaux);
    $this->assertEquals('21:15', $this->creneaux[$n-1]->fin()->heure_texte());
    $this->afficher_creneaux();
  }
  public function testCreerRegimeHebdomadaireUnCreneauJour(): void {
    $code_regime = 1;
    $regime = new Regime_Hebdomadaire($code_regime);
    
    // 1 horaire different pour chaque jour de la semaine
    // une seule plage horaire par jour
    $regime->horaires_journaliers[] = new Plage_Horaire(1, 'PT6H0M0S', 'PT15H0M0S');
    $regime->horaires_journaliers[] = new Plage_Horaire(2, 'PT6H30M0S', 'PT16H0M0S');
    $regime->horaires_journaliers[] = new Plage_Horaire(3, 'PT7H00M0S', 'PT16H0M0S');
    $regime->horaires_journaliers[] = new Plage_Horaire(4, 'PT7H00M0S', 'PT16H0M0S');
    $regime->horaires_journaliers[] = new Plage_Horaire(5, 'PT7H00M0S', 'PT16H0M0S');
    $regime->horaires_journaliers[] = new Plage_Horaire(6, 'PT7H00M0S', 'PT16H0M0S');
    $regime->horaires_journaliers[] = new Plage_Horaire(7, 'PT7H00M0S', 'PT16H0M0S');
    
    $jour = new Instant('2025-10-28'); // mardi
    
    $this->creneaux = $regime->definir_creneaux($jour); // methode sous test
    
    $this->assertEquals('06:30', $this->creneaux[0]->debut()->heure_texte());
    $n = count($this->creneaux);
    $this->assertEquals('15:30', $this->creneaux[$n-1]->fin()->heure_texte());
    $this->afficher_creneaux();
  }
  
}
// ===========================================================================
?>
