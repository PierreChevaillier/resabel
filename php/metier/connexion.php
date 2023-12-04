<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 * description : Definition de la classe Connexion
 *               informations relatives a l'identification et la connexion
 *               d'un utlisateur
 * copyright (c) 2018-2023 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : php - require_once <chemin_vers_ce_fichier_php>
 * dependances :
 * - codification des boolean comme tinyint (0 ou 1) dans la base de donnees
 * utilise avec :
 * - depuis 2023 :
 *   PHP 8.2 sur macOS 13.x
 *   PHP 8.1 sur hebergeur web
 * ----------------------------------------------------------------------------
 * creation :13-nov-2023 pchevaillier@gmail.com
 * revision :
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * -
 * a faire :
 * - dates de derniere connexion, de changement statut {actif, autorise}
 * ============================================================================
 */

require_once 'php/metier/calendrier.php';

// ============================================================================
class Connexion {
  
  private int $code_membre = -1;
  public function def_code_membre(int $valeur): void { $this->code_membre = $valeur; }
  public function code_membre(): int { return $this->code_membre; }

  private string $identifiant = "";
  public function def_identifiant(string $valeur): void { $this->identifiant = $valeur; }
  public function identifiant(): string { return $this->identifiant; }
  
  private string $mot_de_passe = ""; // valeur crypte
  public function def_mot_de_passe(string $valeur): void { $this->mot_de_passe = $valeur; }
  public function mot_de_passe(): string { return $this->mot_de_passe; }
  
  /**
   * Definit l'autorisation de pratiquer une activite du club
   */
  private bool $compte_actif = false;
  public function def_est_compte_actif(int $valeur): void { // pas un 'setter' classique
    if ($valeur != 0 && $valeur != 1)
      throw new InvalidArgumentException();
    $this->compte_actif = ($valeur == 1);
  }
  public function est_compte_actif(): bool { return $this->compte_actif;}
  
  /**
   * Definit s'il s'agit d'un compte correspondant a une personne autorisee a se connecter
   */
  private bool $autorise = false;
  public function est_autorise(): bool { return $this->autorise; }
  public function def_est_autorise(int $valeur): void {  // pas un 'setter' classique
    if ($valeur != 0 && $valeur != 1)
      throw new InvalidArgumentException();
    $this->autorise = ($valeur == 1);
  }
  
  private ? Instant $date_derniere_connexion = null;
  public function date_derniere_connexion(): ? Instant { return $this->date_derniere_connexion; }
  public function def_date_derniere_connexion(Instant $instant): void {
    $this->date_derniere_connexion = $instant;
  }
  
  public function __construct(int $code = -1) {
    $this->code_membre = $code;
  }
  
  public function verifier_mot_passe(string $mdp_clair): bool {
    return password_verify($mdp_clair, $this->mot_de_passe);
  }
  
}
// ============================================================================
?>
