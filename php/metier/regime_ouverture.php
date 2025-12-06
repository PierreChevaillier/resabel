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
 * description : classes supportant les informations sur les heures
 *               d'ouverture et de fermeture des sites d'activite
 * utilisation : php - require_once <chemin_vers_ce_fichier_php>
 * dependances cachees:
 * - aucune
 * ----------------------------------------------------------------------------
 * creation : 30-jun-2019 pchevaillier@gmail.com
 * revision : 25-dec-2019 pchevaillier@gmail.com date_sun_info a la place date_sunrise
 * revision : 13-oct-2024 pchevaillier@gmail.com regime diurne pas de jour uniquement (en attendant mieux)
 * revision : 17-oct-2024 pchevaillier@gmail.com tolerance lever / coucher du soleil
 * revision : 19-mar-2025 pchevaillier@gmail.com cas du jour de passage a l'heure d'ete
 * revision : 25-oct-2025 pchevaillier@gmail.com reorganisation, util. Instant::creer(...)
 * revision : 28-oct-2025 pchevaillier@gmail.com + Regime hebdomadaire::definir_creneaux
 * revision : 29-oct-2025 pchevaillier@gmail.com reorg. + Regime_Journalier
 * ----------------------------------------------------------------------------
 * commentaires :
 * - pas completement stable (pb OCP et LSP)
 * attention :
 * -
 * a faire :
 * - considerer : Lien vers site pour avoir lat et longitude
 *   pour heure lever, coucher soleil ; Mais site *<-->1 regime
 *   attention timeZone
 * - supprimer la 'bidouille' dans Regime_Diurne avec $jour_uniquement = false
 * ============================================================================
 */
  
// ----------------------------------------------------------------------------
require_once 'php/metier/calendrier.php';
  
// ----------------------------------------------------------------------------
abstract class Regime_Ouverture {
  
  public ?DateInterval $decalage_heure_hiver;
  public ?DateInterval $heure_ouverture;
  public ?DateInterval $heure_fermeture;
  public ?DateInterval $duree_seance;
  public $jour_uniquement = true;
  
  private $code = 0;
  public function code(): int { return $this->code; }
  public function def_code(int $valeur) { $this->code = $valeur;}
  
  private $nom = "";
  public function nom(): string { return $this->nom; }
  public function def_nom(string $valeur) { $this->nom = $valeur; }
  
  public function __construct(int $code) {
    $this->code = $code;
    $this->decalage_heure_hiver = new DateInterval('PT0H0M0S');
    $this->duree_seance = new DateInterval('PT1H0M0S');
  }

  protected function definir_heures_ouverture_fermeture(Instant $jour,
                                                     ?Instant & $ouverture,
                                                     ?Instant & $fermeture): void {
    $ouverture = Instant::creer($jour, $this->heure_ouverture);
    $fermeture = Instant::creer($jour, $this->heure_fermeture);
    
    // Application du decalage heure d'hiver (lendemain du changement d'heure)
    if ($jour->heure_hiver()) {
      $ouverture = $ouverture->add($this->decalage_heure_hiver);
      $fermeture = $fermeture->add($this->decalage_heure_hiver);
    }
    return;
  }
  
  protected function creer_creneaux(Instant $ouverture,
                                    Instant $fermeture) {
    $debut_creneau = $ouverture;
    $fin_creneau = $ouverture->add($this->duree_seance);
    $creneaux = array();
    
    while ($fin_creneau <= $fermeture) {
      $creneaux[] = new Intervalle_Temporel($debut_creneau, $fin_creneau);
      $debut_creneau = $debut_creneau->add($this->duree_seance);
      $fin_creneau = $fin_creneau->add($this->duree_seance);
    }
    return $creneaux;
  }
  
  //public abstract function est_creneau_possible($intervalle_temporel);
  public abstract function definir_creneaux(Instant $jour,
                                            float $latitude = 0.0,
                                            float $longitude = 0.0);
}

// ----------------------------------------------------------------------------
  class Regime_Diurne extends Regime_Ouverture {
    
    public ?DateInterval $tolerance_lever_soleil;
    public ?DateInterval $tolerance_coucher_soleil;

    public function __construct(int $code) {
      parent::__construct($code);
      $this->heure_ouverture = new DateInterval('PT0H0M0S');
      $this->heure_fermeture = new DateInterval('PT23H0M0S');
      $this->tolerance_lever_soleil = new DateInterval('PT0H20M0S');
      $this->tolerance_coucher_soleil = new DateInterval('PT0H20M0S');
    }
    
    public function definir_creneaux(Instant $date_jour,
                                     float $latitude = 0.0,
                                     float $longitude = 0.0) {
      
      // Initialisation des dates de debut et fin (ouverture, fermeture)
      $this->definir_heures_ouverture_fermeture($date_jour, $debut, $fin);
      
      // Definition de la plage de creneaux horaires
      $debut_creneau = $debut;
      $fin_creneau = $debut->add($this->duree_seance);
      $creneaux = array();
      
      if ($this->jour_uniquement) {
        // Activite de jour uniquement, donc il faut les heures locales
        // de lever et coucher du soleil
        $info_soleil = date_sun_info($date_jour->getTimestamp(),
                                     $latitude,
                                     $longitude);
        
        $t_lever = $info_soleil['sunrise'];
        //echo 'latitude ', $latitude, ' long. ', $longitude  . PHP_EOL;
        $lever = Calendrier::creer_Instant($t_lever);
        $t_coucher = $info_soleil['sunset'];
        $coucher = Calendrier::creer_Instant($t_coucher);
        
        // Application de la tolerance / nuit
        $lever = $lever->sub($this->tolerance_lever_soleil);
        $coucher = $coucher->add($this->tolerance_coucher_soleil);
        
        while ($fin_creneau <= $fin) {
          if (($debut_creneau > $lever) && ($fin_creneau < $coucher))
            $creneaux[] = new Intervalle_Temporel($debut_creneau, $fin_creneau);
          $debut_creneau = $debut_creneau->add($this->duree_seance);
          $fin_creneau = $fin_creneau->add($this->duree_seance);
        }
      } else {
        // Regime 'diurne' sans tenir compte de l'heure de lever/coucher du soleil
        while ($fin_creneau <= $fin) {
          $creneaux[] = new Intervalle_Temporel($debut_creneau, $fin_creneau);
          $debut_creneau = $debut_creneau->add($this->duree_seance);
          $fin_creneau = $fin_creneau->add($this->duree_seance);
        }
      }
      return $creneaux;
    }

  }

// ----------------------------------------------------------------------------
class Regime_Journalier extends Regime_Ouverture {
  public function definir_creneaux(Instant $jour,
                                   float $latitude = 0.0,
                                   float $longitude = 0.0) {
    
    $this->definir_heures_ouverture_fermeture($jour, $debut, $fin);
    $creneaux = $this->creer_creneaux($debut, $fin);
    return $creneaux;
  }
}

// ----------------------------------------------------------------------------
class Regime_Hebdomadaire extends Regime_Ouverture {
  public $horaires_journaliers = array(); //remarque : pour l'instant 1 seule plage horaire par jour
  public function definir_creneaux(Instant $jour,
                                   float $latitude = 0.0,
                                   float $longitude = 0.0) {
    
    $jour_sem = intval($jour->format('N'));
    foreach ($this->horaires_journaliers as $plage) {
      if ($plage->numero_jour_semaine == $jour_sem) {
        $this->heure_ouverture = $plage->debut;
        $this->heure_fermeture = $plage->fin;
        break;
      }
    }
    
    $this->definir_heures_ouverture_fermeture($jour, $debut, $fin);
    
    $creneaux = $this->creer_creneaux($debut, $fin);
    return $creneaux;
  }
  
}

// ----------------------------------------------------------------------------
class Plage_Horaire {
  public $numero_jour_semaine = 0;
  public ?DateInterval $debut = null;
  public ?DateInterval $fin = null;
  
  public function __construct(int $num_jour,
                              string $heure_debut_texte,
                              string $heure_fin_texte) {
    $this->numero_jour_semaine = $num_jour;
    $this->debut = new DateInterval($heure_debut_texte);
    $this->fin = new DateInterval($heure_fin_texte);
    }
}

// ============================================================================
?>
