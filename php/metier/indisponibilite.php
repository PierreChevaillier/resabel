<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 * description : classes metier
 *               indisponibilite site d'activite ou support activite
 * copyright (c) 2018-2024 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : php - require_once <chemin_vers_ce_fichier_php>
 * dependances :
 * - elements non types
 * utilisation :
 * - depuis 2023 :
 *   PHP 8.2 sur macOS 13.x
 *   PHP 8.1 sur hebergeur web
 * ----------------------------------------------------------------------------
 * creation : 09-jan-2019 pchevaillier@gmail.com
 * revision : 29-jun-2019 pchevaillier@gmail.com
 * revision : 22-fev-2024 pchevaillier@gmail.com * code_objet()
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * -
 * a faire :
 * - typage pour expliciter les dependances et integrite
 * ============================================================================
 */
  
require_once 'php/metier/calendrier.php';
  
// ----------------------------------------------------------------------------
abstract class Indisponibilite {
  public ?Instant $debut = null;
  public ?Instant $fin = null;
  public ?Motif_Indisponibilite $motif = null;

  public $createur; // personne ou anonyme (code = 0)
  public ?Instant $instant_creation = null;
  
  private $information = "";
  public function information(): string { return $this->information; }
  public function def_information(string $valeur): void { $this->information = $valeur; }
  
  private $code = 0;
  public function code(): int { return $this->code; }
  public function def_code(int $valeur): void { $this->code = $valeur;}
  
  public function __construct(int $code) { $this->code = $code; }
  
  public function formatter_periode(): string {
    $texte = "du " . $this->debut->date_texte_court() . " à " . $this->debut->heure_texte();
    $texte = $texte . " au " . $this->fin->date_texte_court() . " à " . $this->fin->heure_texte();
    return $texte;
  }
  
  public abstract function code_objet(): int;
}
  
// ----------------------------------------------------------------------------
class Fermeture_Site extends Indisponibilite {
  public $site_activite = null;
  public function code_objet(): int { return $this->site_activite->code(); }
}

class Indisponibilite_Support extends Indisponibilite {
  public $support = null;
  public function code_objet(): int { return $this->support->code(); }
}

// ----------------------------------------------------------------------------
class Motif_Indisponibilite {
  public $composante_gestionnaire = null; // hors administration resabel
  private $code = 0;
  public function code() { return $this->code; }
  public function def_code($valeur) { $this->code = $valeur;}
  
  private $nom = ""; // utf8
  public function nom() { return $this->nom; }
  public function def_nom($valeur) { $this->nom = $valeur; }
  
  public function __construct($code) { $this->code = $code; }
}

// ============================================================================
?>
