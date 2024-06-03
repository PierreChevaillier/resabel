<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : Definition de la classe Formulaire_Support_Activite
  //               et de ses classes derivees
  //               Formulaire pour la saisie/modification des informations
  //               relatives a un support d'activite
  // copyright (c) 2018-2024 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <nom_-fichier.php>
  // dependances :
/* utilise avec :
 * - depuis 2023 :
 *   PHP 8.2 sur macOS 13.x
 *   PHP 8.1 sur hebergeur web
 */
  // --------------------------------------------------------------------------
  // creation : 30-aug-2020 pchevaillier@gmail.com
  // revision : 03-jun-2024 pchevaillier@gmail.com + site_activite + type_support
  // --------------------------------------------------------------------------
  // commentaires :
  // - en cours d'evolution
  // attention :
  // - 
  // a faire :
// - nombre de postes pour les ergos.
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/formulaire.php';
  require_once 'php/elements_page/generiques/champ_formulaire.php';

require_once 'php/metier/support_activite.php';
require_once 'php/bdd/enregistrement_support_activite.php';
require_once 'php/bdd/enregistrement_type_support_activite.php';
require_once 'php/bdd/enregistrement_site_activite.php';

  // ==========================================================================
  class Formulaire_Support_Activite extends Formulaire {
    
    private $support = null;
    
    public function __construct($page, $support) {
      
      $this->support = $support;
      $this->message_bouton_validation = "Valider";
      $this->confirmation_requise = true;
      
      // Parametrage de l'appel du script php qui traite
      // les donnees saisies ou modifiees grace au formulaire
      $script_traitement = 'php/scripts/support_activite_info_maj.php?';
      $params = 'a=' . $_GET['a'] . '&typ=' . $_GET['typ'];
      if (isset($_GET['a']) && $_GET['a'] == 'm')
        $params = $params . (isset($this->support) ? '&sua=' . $this->support->code() : '');
      $script_traitement = $script_traitement . $params;
        
      $action = $_GET['a'];
      $id = 'form_sup_act';
      
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
        $item = new Champ_Selection("site");
        $item->def_titre("Site où est basé le support");
        $item->def_obligatoire();
        $item->valeurs_multiples = False;
        $selection = '';
        if (is_a($this->support->site_base, 'Site_Activite_Mer'))
          $selection = ' site.code_type = ' . Enregistrement_Site_Activite::CODE_TYPE_SITE_MER;
        else
          $selection = ' site.code_type = ' . Enregistrement_Site_Activite::CODE_TYPE_SALLE_SPORT;
        $sites = array();
        Enregistrement_Site_Activite::collecter($selection, "", $sites);
        foreach ($sites as $code => $site)
          $item->options[$code] = $site->nom();
        $this->ajouter_champ($item);
        
        $item = new Champ_Binaire("actif", "", "");
        $item->def_titre("Actif (utilisable pour activités)");
        $this->ajouter_champ($item);

        $item = new Champ_Selection("type");
        $item->def_titre("Type de support");
        $item->def_obligatoire();
        $item->valeurs_multiples = False;
        $code_classe_support = 0;
        if (is_a($this->support, 'Bateau'))
          $code_classe_support = Enregistrement_Support_Activite::CODE_TYPE_BATEAU;
        else
          $code_classe_support =  Enregistrement_Support_Activite::CODE_TYPE_PLATEAU_ERGO;
        $types_support = array();
        Enregistrement_Type_Support_Activite::collecter_identites($code_classe_support, $types_support);
        foreach ($types_support as $code => $type_support)
          $item->options[$code] = $type_support->nom();
        $this->ajouter_champ($item);
        
        $item = new Champ_Nom("nom", "js/controle_saisie_nom.js", "verif_nom");
        $item->def_titre("Nom");
        $item->def_obligatoire();
        $this->ajouter_champ($item);
        
        $item = new Champ_Nom("num", "js/controle_saisie_alphanum.js", "verif_alphanum");
        $item->def_titre("Numéro");
        $item->def_obligatoire();
        $this->ajouter_champ($item);
        
        $item = new Champ_Nom("mdl", "", "");
        $item->def_titre("Modèle");
        $this->ajouter_champ($item);

        $item = new Champ_Nom("const", "js/controle_saisie_nom.js", "verif_nom");
        $item->def_titre("Constructeur");
        $this->ajouter_champ($item);

        $item = new Champ_Entier_Naturel("aconst", "", "");
        $item->def_titre("Année de construction");
        $item->valeur_min = 1900;
        $item->valeur_max = date('Y') + 1;
        $this->ajouter_champ($item);
        
        $item = new Champ_Binaire("compet", "", "");
        $item->def_titre("Pour la compétition");
        $this->ajouter_champ($item);

        $item = new Champ_Binaire("loisir", "", "");
        $item->def_titre("Pour le loisir");
        $this->ajouter_champ($item);

        $item = new Champ_Entier_Naturel("mininit", "", "");
        $item->def_titre("Nombre places minimum pour initiation");
        $item->valeur_min = 0;
        $this->ajouter_champ($item);
       
        $item = new Champ_Entier_Naturel("maxinit", "", "");
        $item->def_titre("Nombre places maximum pour initiation");
        $item->valeur_min = 0;
        $this->ajouter_champ($item);

        parent::initialiser();
      } catch(Exception $e) {
        die('Exception dans la methode initialiser de la classe Formulaire_Support_Activite : ' . $e->getMessage());
      }
    }
    
    public function initialiser_champs() {
      $ok = isset($this->support);
      if ($ok) {
        if (!is_null($this->support->site_base)) {
          $this->champ('site')->def_valeur($this->support->site_base->code());
        }
        
        if (!is_null($this->support->type)) {
          $this->champ('type')->def_valeur($this->support->type->code());
        }
        
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
        if (!is_null($this->support->nombre_initiation_min))
          $this->champ('mininit')->def_valeur($this->support->nombre_initiation_min);
        if (!is_null($this->support->nombre_initiation_max))
          $this->champ('maxinit')->def_valeur($this->support->nombre_initiation_max);
      }
      return $ok;
    }
  }
  // ==========================================================================
?>
