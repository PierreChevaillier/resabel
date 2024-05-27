<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 * description : Definition de la classe Profil_Session
 * copyright (c) 2018-2024 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : php - require_once <chemin_vers_ce_fichier_php>
 * dependances :
 * - $_SESSION
 * utilise avec :
 * - depuis 2023 :
 *   PHP 8.2 sur macOS 13.x
 *   PHP 8.1 sur hebergeur web
 * ----------------------------------------------------------------------------
 * creation : 22-may-2024 pchevaillier@gmail.com
 * revision :
 * ----------------------------------------------------------------------------
 * commentaires :
 * - pattern GoF Singleton
 * attention :
 * -
 * a faire :
 * - nb_site_actif (a voir)
 * ============================================================================
 */

// --- Classes utilisees

// ============================================================================
class Profil_Session {

  private static ? Profil_Session $instance;
  public static function accede(): ?Profil_Session { return self::$instance; }
  
  public function __construct() {
    self::$instance = $this;
    $this->initialiser();
  }
  
  public function est_club(): bool { return ! $this->personne; }
  
  private $personne = false;
  public function est_personne(): bool { return $this->personne; }
  
  private $admin = false;
  public function est_admin(): bool { return $this->admin; }
  
  private $membre_actif = false;
  public function est_membre_actif(): bool { return $this->membre_actif; }
  
  private $responsable = false;
  public function est_responsable(): bool { return $this->responsable; }
  
  private $permanence = false;
  public function est_permanence(): bool { return $this->permanence; }
  
  private function initialiser(): void {
    $this->personne = isset($_SESSION['prs']) && $_SESSION['prs'];
    $this->admin = isset($_SESSION['adm']) && $_SESSION['adm'];
    if ($this->personne) {
      $this->membre_actif = isset($_SESSION['usr']) && isset($_SESSION['act']) && $_SESSION['act'];
      $this->responsable = isset($_SESSION['usr']) && isset($_SESSION['cdb']) && $_SESSION['cdb'];
      $this->permanence = isset($_SESSION['prm']) && $_SESSION['prm'];
    }
  }
}
// ============================================================================
?>
