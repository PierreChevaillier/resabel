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
 * revision : 25-dec-2019 pchevaillier@gmail.com modif definir_creneaux
 * revision : 25-dec-2019 pchevaillier@gmail.com date_sun_info a la place date_sunrise
 * revision : 13-oct-2024 pchevaillier@gmail.com regime diurne pas de jour uniquement (en attendant mieux)
 * revision : 17-oct-2024 pchevaillier@gmail.com tolerance lever / coucher du soleil
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * -
 * a faire :
 * - Lien vers site pour avoir lat et longitude pour heure lever, coucher soleil
 *   attention timeZone
 *  - classe regime journalier (tous les jours de la semaine le meme ,
 *   sans ternir compte du lever / coucher soleil cf. la 'bidouille' dans Regime_Diurne
 *  - classe Regime Hebdomadaire pas faite
 * ============================================================================
 */
  
// ----------------------------------------------------------------------------
require_once 'php/metier/calendrier.php';
  
// ----------------------------------------------------------------------------
abstract class Regime_Ouverture {
  
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
    $this->duree_seance = new DateInterval('PT1H0M0S');
  }
  
  //public abstract function est_creneau_possible($intervalle_temporel);
  public abstract function definir_creneaux(Instant $jour,
                                            float $latitude,
                                            float $longitude);
}

// ----------------------------------------------------------------------------
  class Regime_Diurne extends Regime_Ouverture {
    public ?DateInterval $decalage_heure_hiver;
    public ?DateInterval $heure_ouverture;
    public ?DateInterval $heure_fermeture;

    public function __construct(int $code) {
      parent::__construct($code);
      $this->decalage_heure_hiver = new DateInterval('PT0H0M0S');
      $this->heure_ouverture = new DateInterval('PT0H0M0S');
      $this->heure_fermeture = new DateInterval('PT23H0M0S');
    }
    
    public function definir_creneaux(Instant $date_jour,
                                     float $latitude,
                                     float $longitude) {
      
      // Activite de jour uniquement, donc il faut les heures locales
      // de lever et coucher du soleil
      $tolerance_lever_soleil = new DateInterval('PT0H20M0S');
      $tolerance_coucher_soleil = new DateInterval('PT0H20M0S');
      
      $info_soleil = date_sun_info($date_jour->getTimestamp(),
                                   $latitude,
                                   $longitude);
      
      $t_lever = $info_soleil['sunrise'];
      //echo 'latitude ', $latitude, ' long. ', $longitude;
      $lever = Calendrier::creer_Instant($t_lever);
      $lever = $lever->sub($tolerance_lever_soleil);
      
      $t_coucher = $info_soleil['sunset'];
      //echo ' Lever ', $lever->format('d-m-Y H:i:s');
      $coucher = Calendrier::creer_Instant($t_coucher);
      $coucher = $coucher->add($tolerance_coucher_soleil);
      //echo 'Coucher ', $coucher->format('d-m-Y H:i:s');
      
      // Initialisation des dates de debut et fin (ouverture, fermeture)
      
      $debut = $date_jour->add($this->heure_ouverture);
      //echo 'Debut ', $debut->format('d-m-Y H:i:s');
      $fin = $date_jour->add($this->heure_fermeture);
      //echo 'Fin ', $fin->format('d-m-Y H:i:s');
      
      //$heure_hiver = (1 - date('I', $jour));
      if ($date_jour->heure_hiver() && isset($this->decalage_heure_hiver)) {
        $debut = $debut->add($this->decalage_heure_hiver);
        $fin = $fin->add($this->decalage_heure_hiver);
      }
      
      $debut_creneau = $debut;
      $fin_creneau = $debut->add($this->duree_seance);
      $creneaux = array();
      
      if ($this->jour_uniquement) {
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
class Regime_Hebdomadaire extends Regime_Ouverture {
  public $horaires_journaliers = array(); //Plages horaires ; remarque : potentiellement plusieurs plages horaires par jour
  public function definir_creneaux(Instant $jour,
                                   float $latitude,
                                   float $longitude) {
  }

}

// ----------------------------------------------------------------------------
class Plage_Horaire {
  public $numero_jour_semaine = 0;
  public $heure_ouverture; // DateInterval
  public $heure_fermeture; // DateInterval
}
    
// ============================================================================
?>
