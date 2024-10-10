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
 * description : classe Permanence - personne resp. organisation des sorties
 * utilisation : php - require_once <chemin_vers_ce_fichier_php>
 * dependances :
 * - aucune
 * utilise avec :
 * - depuis 2023 :
 *   PHP 8.2 sur macOS 13.x
 *   PHP 8.1 sur hebergeur web
 * ----------------------------------------------------------------------------
 * creation : 28-mai-2019 pchevaillier@gmail.com
 * revision : 28-dec-2019 pchevaillier@gmail.com impact refonte Calendrier
 * revision : 09-oct-2024 pchevaillier@gmail.com typage
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * -
 * a faire :
 * -
 * ============================================================================
 */

require_once 'php/metier/calendrier.php';
require_once 'php/metier/personne.php';
  
// ============================================================================
class Permanence {
  
  public $responsable = null;
  public function responsable(): ?Personne { return $this->responsable; }
  public function def_responsable(Personne $personne): void { $this->responsable = $personne;}
  
  private $semaine = 0;
  public function semaine(): int { return $this->semaine; }
  //public function def_semaine($valeur) { $this->semaine = $valeur; }
  
  private $annee = 0;
  public function annee(): int { return $this->annee; }
  //public function def_annee($valeur) { $this->annee = $valeur; }
  
  public function code_responsable(): int { return $this->responsable->code(); }
  
  public function __construct(int $semaine, int $annee) {
    $this->semaine = $semaine;
    $this->annee = $annee;
  }
  /*
   * Creation de la permanence pour le semaine en cours
   * TODO: retourner la nouvelle permanence, ce sera plus clair
   */
  public static function cette_semaine(?Permanence & $permanence): void {
    $j = Calendrier::aujourdhui();
    $permanence = new Permanence($j->numero_semaine(), Calendrier::annee_semaine($j));
    return;
  }
  
  public function egale(Permanence $perm): bool {
    return (($this->annee == $perm->annee) && ($this->semaine == $perm->semaine));
  }
  
  public function jour(): Instant {
    return Calendrier::date_jour_semaine(6, $this->semaine, $this->annee);
  }
  
  public function jour_texte(): string {
    return $this->jour()->date_texte();
  }
}

// ============================================================================
?>
