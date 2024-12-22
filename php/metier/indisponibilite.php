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
 * revision : 16-may-2024 pchevaillier@gmail.com * code_objet(), libelle_objet
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
require_once 'php/metier/support_activite.php';
require_once 'php/metier/site_activite.php';
// ----------------------------------------------------------------------------
abstract class Indisponibilite {
  
  // --- Proprietes relatives a la periode d'indisponibilite
  private ?Intervalle_Temporel $periode;
  
  public function debut(): ?Instant { return $this->periode->debut(); }
  public function fin(): ?Instant { return $this->periode->fin(); }
  
  public function definir_periode(Instant $debut, Instant $fin): bool {
    $this->periode = new Intervalle_Temporel($debut, $fin);
    return $debut->est_avant($fin);
  }

  // --- Proprietes relatives au motif d'indisponibilite
  private ?Motif_Indisponibilite $motif = null;
  public function motif(): ?Motif_Indisponibilite { return $this->motif; }
  public function def_motif(Motif_Indisponibilite $motif) { $this->motif = $motif; }
  public function libelle_motif(): string {
    $resultat = !is_null($this->motif)? $this->motif->nom(): "motif non défini";
    return $resultat;
  }

  // --- Proprietes relatives a la personne ayant cree l'indisponibilite
  private ?Personne $createurice = null; // personne ou anonyme (code = 0)
  public function createurice(): ? Personne {
    return $this->createurice;
  }
  public function def_createurice(Personne $personne): void {
    $this->createurice = $personne;
  }
    
  public function identite_createurice(): string {
    $resultat = "anonyme";
    if (!is_null($this->createurice))
      $resultat = $this->createurice->prenom() . ' ' . $this->createurice->nom();
    return $resultat;
  }
    
  private ?Instant $instant_creation = null;
  public function def_instant_creation(Instant $instant) { $this->instant_creation = $instant; }
  public function instant_creation(): ?Instant { return $this->instant_creation; }
    
 // Information specifique reseignee
  private $information = "";
  public function information(): string { return $this->information; }
  public function def_information(string $valeur): void { $this->information = $valeur; }
  
  private $code = 0;
  public function code(): int { return $this->code; }
  public function def_code(int $valeur): void { $this->code = $valeur;}
  
  public function __construct(int $code) {
    $this->code = $code;
  }
  
  public function formatter_periode(): string {
    $texte = "du " . $this->debut()->date_texte_abbr() . " à " . $this->debut()->heure_texte();
    $texte = $texte . "<br />au " . $this->fin()->date_texte_abbr() . " à " . $this->fin()->heure_texte();
    return $texte;
  }
  
  public abstract function code_objet(): ?int;
  public abstract function libelle_objet(): ?string;
  
}
  
// ----------------------------------------------------------------------------
class Fermeture_Site extends Indisponibilite {
  public $site_activite = null;
  public function def_site_activite(Site_Activite $site): void {
    $this->site_activite = $site;
  }
  public function code_objet(): ? int {
    $result = (! is_null($this->site_activite)) ? $this->site_activite->code(): null;
    return $result;
  }
  public function libelle_objet(): ? string {
    $result = (! is_null($this->site_activite)) ? $this->site_activite->nom(): null;
    return $result;
  }
}

class Indisponibilite_Support extends Indisponibilite {
  public $support = null;
  public function def_support(Support_Activite $support): void {
    $this->support = $support;
  }
  public function code_objet(): ? int {
    $result = (! is_null($this->support)) ? $this->support->code(): null;
    return $result;
    }
    
  public function libelle_objet(): ? string {
    $result = (! is_null($this->support)) ? $this->support->nom(): null;
    return $result;
  }
}

// ----------------------------------------------------------------------------
class Motif_Indisponibilite {
  public $composante_gestionnaire = null; // hors administration resabel
  private $code = 0;
  public function code(): int { return $this->code; }
  public function def_code(int $valeur): void { $this->code = $valeur;}
  
  private $nom = ""; // utf8
  public function nom(): string { return $this->nom; }
  public function def_nom(string $valeur): void { $this->nom = $valeur; }
  
  public function __construct(int $code) { $this->code = $code; }
}

// ============================================================================
?>
