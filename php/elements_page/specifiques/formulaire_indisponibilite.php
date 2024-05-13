<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 * description : Definition de la classe Formulaire_Indisponibilite
 *               Formulaire de saisie/modification des informations relatives
 *               a une fermeture de site ou indisponibilite d'un support d'activite
 * copyright (c) 2018-2024 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : php - require_once <chemin_vers_ce_fichier_php>
 * dependances :
 * - bootstrap 5.x
 * utilise avec :
 * - depuis 2023 :
 *   PHP 8.2 sur macOS 13.x
 *   PHP 8.1 sur hebergeur web
 * ----------------------------------------------------------------------------
 * creation : 13-mai-2024 pchevaillier@gmail.com
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
require_once 'php/metier/indisponibilite.php';

require_once 'php/elements_page/generiques/formulaire.php';
require_once 'php/elements_page/generiques/champ_formulaire.php';
require_once 'php/elements_page/generiques/page.php';

require_once 'php/bdd/enregistrement_site_activite.php';
require_once 'php/bdd/enregistrement_support_activite.php';
// ============================================================================
class Formulaire_Indisponibilite extends Formulaire {
  
  private ?Indisponibilite $indisponibilite = null;
  
  public function __construct(Page $page,
                              Indisponibilite $indisponibilite) {
    
    $this->indisponibilite = $indisponibilite;
    $this->message_bouton_validation = "Valider";
    $this->confirmation_requise = true;
    
    // Parametrage de l'appel du script php qui traite
    // les donnees saisies ou modifiees grace au formulaire
    $script_traitement = 'php/scripts/indisponibilite_maj.php?';
    $action = $_GET['act'];
    $params = 'act=' . $action . '&typ=' . $_GET['typ'];
    if (isset($action) && $action == 'm')
      $params = $params . (isset($this->indisponibilite) ? '&id=' . $this->indisponibilite->code() : '');
    $script_traitement = $script_traitement . $params;
      
    $id = 'form_indisp';
    parent::__construct($page, $script_traitement, $action, $id);
    
  }
  
  public function initialiser() {
    
    $item = null;
    /*
    if (isset($_GET['a']) && $_GET['a'] == 'c') {
      $code_chargement = new Element_Code();
      $script = "\n<script>window.onload = function() {creer_gestionnaire_evenement('id', 'prn', 'nom'); };</script>\n";
      $code_chargement->def_code($script);
      $this->page->ajoute_contenu($code_chargement);
    }
    */
    try {
      
      if (is_a($this->indisponibilite, 'Fermeture_Site')) {
        $item = new Champ_Selection("site");
        $item->def_titre("Site fermé");
        $item->def_obligatoire();
        $item->valeurs_multiples = False;
        $sites = array();
        Enregistrement_Site_Activite::collecter("","", $sites);
        foreach ($sites as $code => $site)
          $item->options[$code] = $site->nom();
        $this->ajouter_champ($item);
      }
      
      if (is_a($this->indisponibilite, 'Indisponibilite_Support')) {
        $item = new Champ_Selection("support");
        $item->def_titre("Support(s) indisponible(s)");
        $item->def_obligatoire();
        $item->valeurs_multiples = true;
        $supports = array();
        Enregistrement_Support_Activite::collecter("","", $supports);
        foreach ($supports as $code => $support)
          $item->options[$code] = $support->numero() . ' ' . $support->nom();
        $this->ajouter_champ($item);
      }
      
      $item = new Champ_Date("date_deb", "", "");
      $item->def_titre("Date de début");
      $item->def_obligatoire();
      $this->ajouter_champ($item);

      $item = new Champ_Heure("hre_deb", "", "");
      $item->def_titre("Heure de début");
      $item->def_obligatoire();
      $this->ajouter_champ($item);
      
      $item = new Champ_Date("date_fin", "", "");
      $item->def_titre("Date de fin");
      $item->def_obligatoire();
      $this->ajouter_champ($item);

      $item = new Champ_Heure("hre_fin", "", "");
      $item->def_titre("Heure de fin");
      $item->def_obligatoire();
      $this->ajouter_champ($item);
      
      $item = new Champ_Selection("motif");
      $item->def_titre("Motif");
      $item->def_obligatoire();
      $item->valeurs_multiples = False;
      $motifs = array();
      //Enregistrement_Commune::collecter("acces = 'O'"," nom ASC", $communes);
      foreach ($motifs as $code => $c)
        $item->options[$code] = $c->nom();
      $this->ajouter_champ($item);
      
      $item = new Champ_Texte("desc", "", "");
      $item->def_titre("Explication");
      $this->ajouter_champ($item);
      
      parent::initialiser();
    } catch(Exception $e) {
      die('Exception dans la methode initialiser de la classe Formulaire_Support_Activite : ' . $e->getMessage());
    }
  }
  
  public function initialiser_champs() {
    $ok = isset($this->indisponibilite);
    if ($ok) {
      /*
      $this->champ('nom')->def_valeur($this->support->nom());
      $this->champ('num')->def_valeur($this->support->numero());
      $this->champ('mdl')->def_valeur($this->support->modele);
      $this->champ('const')->def_valeur($this->support->constructeur);
      $this->champ('aconst')->def_valeur($this->support->annee_construction);
      $v = $this->support->est_pour_competition() ? 1 : 0;
      $this->champ('compet')->def_valeur($v);
      $v = $this->support->est_pour_loisir() ? 1 : 0;
      $this->champ('loisir')->def_valeur($v);
      $v = $this->support->est_actif() ? 1 : 0;
      $this->champ('actif')->def_valeur($v);
      $this->champ('mininit')->def_valeur($this->support->nombre_initiation_min);
      $this->champ('maxinit')->def_valeur($this->support->nombre_initiation_max);
       */
    }
    return $ok;
  }
}
// ============================================================================
?>
