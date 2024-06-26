<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : formulaire pour recherche disponibilite support activite
  // copyright (c) 2018-2020 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_fichier.php>
  // dependances :
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.3 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 10-jul-2019 pchevaillier@gmail.com
  // revision : 21-mar-2020 pchevaillier@gmail.com val. defaut creneaux horaires
  // --------------------------------------------------------------------------
  // commentaires :
  // - pas complet
  // attention :
  // - 
  // a faire :
  // - cas ou un seul site actif.
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/formulaire.php';
  require_once 'php/elements_page/generiques/champ_formulaire.php';

  require_once 'php/metier/calendrier.php';
  
  require_once 'php/metier/club.php';
  require_once 'php/bdd/enregistrement_club.php';
  
  require_once 'php/metier/site_activite.php';
  require_once 'php/bdd/enregistrement_site_activite.php';
  
  require_once 'php/metier/support_activite.php';
  require_once 'php/bdd/enregistrement_support_activite.php';
  
  require_once 'php/metier/regime_ouverture.php';
  require_once 'php/bdd/enregistrement_regime_ouverture.php';
  
  // ==========================================================================
  class Formulaire_Disponibilite_Activite extends Formulaire {
    
    private $club;
    private $code_site_selectionne;
    private $sites = null;
    private $supports_activite = null;
    
    public function __construct($page, $mode, $code_site_selectionne) {
      //$this->mode = $mode;
      $this->code_site_selectionne = $code_site_selectionne;
      $this->collecter_informations();
      
      $this->def_titre("Critères de recherche");
      $this->message_bouton_validation = "Rechercher disponibilités";
      $this->methode = 'get';
      $script_traitement = "activites.php";// ?a=" . $this->mode . "&j=" . $_GET['cnx'];
      $action = $mode;
      $id = 'form_disp_act';
      $page->javascripts[] = 'js/raz_formulaire.js';
      $page->javascripts[] = 'js/controle_dispo_activite.js'; // pour info contextuelle sur horaires et supports
      parent::__construct($page, $script_traitement, $action, $id);
    }
    
    protected function collecter_info_club() {
      $code_club = isset($_SESSION['clb']) ? $_SESSION['clb'] : 1;
      $this->club = new Club($code_club);
      $enreg = new Enregistrement_Club();
      $enreg->def_club($this->club);
      $enreg->lire();
    }
    
    protected function collecter_info_sites() {
      // Sites actifs, tries par type
      Enregistrement_Site_Activite::collecter(" actif = 1 ", " code_type ",  $this->sites);
    }
    
    protected function collecter_info_supports_actifs() {
      $supports = array();
      $site = $this->sites[$this->code_site_selectionne];
      Enregistrement_Support_Activite::collecter("support.code_site_base = " . $site->code() . " AND support.actif = 1 ",
                                                 " support.code_type_support DESC, support.code ASC",
                                                 $supports);
      $this->supports_activite =  $supports;
    }
    
    private function collecter_informations() {
      $this->collecter_info_club(); // pour le fuseau horaire et les sites
      $this->collecter_info_sites();
      $this->collecter_info_supports_actifs();
    }
    
    public function initialiser_champs() {
      $this->initialiser_dates('j');
      $this->initialiser_creneaux('pc', 'dc');
      
      //$this->champ('id')->def_valeur($this->membre->identifiant);
      $this->initialiser_sites('sa');
      $this->initialiser_types_support('ts');
      $this->initialiser_supports('s');
    }
    
    private function initialiser_dates($id_champ) {
      $nJours = 21;
      $dates = array(); // timestamp, texte_court
      Calendrier::jours_futurs_texte($nJours, $dates);
      $this->champ($id_champ)->options = $dates;
    }

    private function initialiser_creneaux($id_premier, $id_dernier) {
      $site = $this->sites[$this->code_site_selectionne];
      $site->regime_ouverture = Enregistrement_Regime_ouverture::creer($site->code_regime_ouverture());
      $date_ref = Calendrier::aujourdhui();
      $creneaux_activite = $site->regime_ouverture->definir_creneaux($date_ref,
                                                                     $site->latitude,
                                                                     $site->longitude);
      $possibilites = array();
      $rang = 0;
      // valeurs par defaut : 1er et 2e creneau
      foreach ($creneaux_activite as $creneau) {
        $cle = $creneau->debut()->valeur_cle_horaire(); //$creneau->getTimestamp();
        $label = $creneau->debut()->format("H:i");
        if ($rang == 0)
          $this->champ($id_premier)->def_valeur($cle);
        elseif ($rang == 1)
          $this->champ($id_dernier)->def_valeur($cle);
        $possibilites[$cle] = $label;
        $rang += 1;
      }
      if ($rang == 1)
        $this->champ($id_dernier)->def_valeur($this->champ($id_premier)->valeur());

      $this->champ($id_premier)->options = $possibilites;
      $this->champ($id_dernier)->options = $possibilites;
    }
      
    private function initialiser_sites($id_champ) {
      if (count($this->sites) == 1) {
        foreach ($this->sites as $code => $dummy)
          $this->champ($id_champ)->def_valeur($code);
      } else {
        $possibilites = array();
        foreach ($this->sites as $site)
          $possibilites[$site->code()] = $site->nom();
        $this->champ($id_champ)->options = $possibilites;
      }
    }
    
    private function initialiser_types_support($id_champ) {
      $possibilites = array();
      $possibilites[0] = "Tous types";
      foreach ($this->supports_activite as $support)
        $possibilites[$support->type->code()] = $support->type->nom();
      $this->champ($id_champ)->options = $possibilites;
      $this->champ($id_champ)->def_valeur(0); // tous
    }
    
    private function initialiser_supports($id_champ) {
      $possibilites = array();
      $possibilites[0] = "Tous";
      $site = $this->sites[$this->code_site_selectionne];
      foreach ($this->supports_activite as $support)
          $possibilites[$support->code()] = $support->identite_texte(); //$support->numero() . ' ' . $support->nom() . ' (' .  $support->type->nom() . ')';
      $this->champ($id_champ)->options = $possibilites;
      $this->champ($id_champ)->def_valeur(0); // tous
    }
    
    public function initialiser() {
      $item = null;
      $code_chargement = new Element_Code();
      $script = "\n<script>window.onload = function() {creer_gestionnaire_evenement('j', 'sa', 'pc', 'dc', 'ts', 's'); };</script>\n";
      $code_chargement->def_code($script);
      $this->page->ajoute_contenu($code_chargement);
      try {
        // Date du jour (parmi valeurs possibles)
        $id = 'j';
        $item = new Champ_Selection($id);
        $item->def_titre("Date");
        $item->valeurs_multiples = false;
        $this->ajouter_champ($item);
        
        // Site d'activite
        $id = 'sa';
        if (isset($this->sites) && (count($this->sites) > 1)) {
          // Plusieurs sites sont actifs : on pose donc la question
          $item = new Champ_Selection($id);
          $item->def_titre("Site");
          $item->valeurs_multiples = false;
        } else {
          // Un seul site : on ne pose pas la question
          $item = new Champ_Cache($id);
        }
        $item->def_valeur($this->code_site_selectionne);
        $this->ajouter_champ($item);
        
        // Creneaux horaires (premier creneau - dernier creneau)
        $id = 'pc';
        $item = new Champ_Selection($id);
        $item->def_titre("Premier créneau");
        $item->valeurs_multiples = false;
        $this->ajouter_champ($item);

        $id = 'dc';
        $item = new Champ_Selection($id);
        $item->def_titre("Dernier créneau");
        $item->valeurs_multiples = false;
        $this->ajouter_champ($item);
        
        $id = 'ts';
        $item = new Champ_Selection($id);
        $item->def_titre("Type de support");
        $item->valeurs_multiples = false;
        $this->ajouter_champ($item);
        
        $id = 's';
        $item = new Champ_Selection($id);
        $item->def_titre("Support");
        $item->valeurs_multiples = false;
        $this->ajouter_champ($item);
        
        //Enregistrement_Commune::collecter("acces = 'O'"," nom ASC", $communes);
        //$item->options[0] = "Toutes";
        //foreach ($possibilites as $code => $elem)
        //  $item->options[$code] = $elem->nom();
        //if (isset($_POST[$id]))
        //  $item->def_valeur($_POST[$id]);
        parent::initialiser();
      } catch(Exception $e) {
        die('Exception dans la methode initialiser de la classe Formulaire_Disponibilite_Activite : ' . $e->getMessage());
      }
    }
    
    protected function afficher_corps() {
      parent::afficher_corps();
      /*
       Ca ne marche pas tel quel : il faudrait un script specifique
       a la place de raz_valeurs_formulaire.
       Pas certain que ce soit necessaire.
      echo '<div class="form-group form-btn" id="btn_raz" >';
      echo '<input type="button" class="btn btn-large btn-outline-secondary"';
      echo ' onclick="return raz_valeurs_formulaire(' . $this->id() . ')"';
      echo ' value="Supprimer les critères de sélection" >';
      echo '</div>';
       */
      
    }
  }
  // ==========================================================================
?>
