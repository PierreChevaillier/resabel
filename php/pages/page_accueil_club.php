<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 * description : Definition de la classe Page_Accueil_Club
 *               Sorte de portail / session club
 * copyright (c) 2018-2024 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : php - require_once <chemin_vers_ce_fichier_php>
 * dependances :
 * - aucune
 * utilise avec :
 * - depuis 2023 :
 *   PHP 8.2 sur macOS 13.x
 *   PHP 8.1 sur hebergeur web
 * ----------------------------------------------------------------------------
 * creation : 03-jun-2024 pchevaillier@gmail.com depuis page_accueil_perso.php
 * revision :
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * -
 * a faire :
 * -
 * ============================================================================
 */

// --- Classes utilisees
require_once 'php/elements_page/specifiques/page_menu.php';
require_once 'php/elements_page/generiques/entete_contenu_page.php';
require_once 'php/elements_page/generiques/entete_section.php';

require_once 'php/metier/calendrier.php';

require_once 'php/metier/site_activite.php';
require_once 'php/bdd/enregistrement_site_activite.php';

require_once 'php/metier/personne.php';
require_once 'php/bdd/enregistrement_membre.php';

require_once 'php/metier/support_activite.php';
require_once 'php/bdd/enregistrement_support_activite.php';


require_once 'php/elements_page/specifiques/vue_personne.php';

// Affichage permanence de la semaine actuelle
require_once 'php/metier/permanence.php';
require_once 'php/bdd/enregistrement_permanence.php';
require_once 'php/elements_page/specifiques/vue_permanence.php';

// affichage des marees
require_once 'php/elements_page/generiques/conteneur_repliable.php';
require_once 'php/metier/maree.php';
  
// ----------------------------------------------------------------------------
class Page_Accueil_Club extends Page_Menu {
  
  private ?Instant $maintenant = null;
  private $contexte = null;
  
  private $sites = array();
  private ?Permanence $permanence = null;
  
  public function __construct($nom_site_web, $nom_page, $liste_feuilles_style = null) {
  
    $this->maintenant = Calendrier::maintenant();
    
    //$this->contexte->initialiser();
    
    //$this->code_utilisateur = $_SESSION['usr'];
    
    $this->collecter_informations();
    parent::__construct($nom_site_web, $nom_page, $liste_feuilles_style);
  }

  protected function collecter_informations(): void {
    $this->collecter_info_sites();
    $this->collecter_info_permanence();
  }
  
  protected function collecter_info_sites(): void {
    Enregistrement_Site_Activite::collecter("", " code_type ",  $this->sites);
  }
  
  protected function collecter_info_permanence(): void {
    $jour = $this->maintenant->jour();
    $sem = $jour->format("W");
    $annee = Calendrier::annee_semaine($jour);
    $this->permanence = new Permanence($sem, $annee);
    $enregistrement_permanence = new Enregistrement_Permanence();
    $enregistrement_permanence->def_permanence($this->permanence);
    $enregistrement_permanence->lire();
   }

  
  public function definir_elements() {
    parent::definir_elements();
    
    $element = new Entete_Section();
    $maintenant = Calendrier::maintenant();
    $aujourdhui = $maintenant->jour();
    $element->def_titre($aujourdhui->date_texte());
    $this->ajoute_contenu($element);
    
    $this->definir_affichage_permanence();
    $this->definir_affichage_marees();
    
  }
  
  protected function definir_affichage_permanence() {
    if (!is_null($this->permanence)) {
      $cadre = new Conteneur_Repliable();
      $this->ajoute_contenu($cadre);
      $cadre->def_id('cadre_perm');
      $cadre->def_titre("Permanence semaine");
      $afficheur_permanence = new Afficheur_Responsable_Permanence($this);
      $cadre->ajouter_element($afficheur_permanence);
      $afficheur_permanence->permanence = $this->permanence;
    }
  }

  protected function definir_affichage_marees() {
    $cadre = new Conteneur_Repliable();
    $cadre->def_id('cadre_maree');
    $cadre->def_titre("Marées");
    
    $this->ajoute_contenu($cadre);
    $code_site = 1;
    $maintenant = Calendrier::maintenant();
    $marees = Enregistrement_Maree::recherche_marees_jour($code_site,  $maintenant->jour());
    $table_marees = new Table_Marees_jour($marees);
    $cadre->ajouter_element($table_marees);
  }
  
}
// ============================================================================
?>
