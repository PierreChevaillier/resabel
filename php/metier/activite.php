<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : Classes pour information et planification activite journaliere
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : 
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 09-jun-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // - Uniquement 'logique metier': pas d'IHM
  // attention :
  // - En chantier...
  // - non stabilise
  // a faire :
  // -
  // ==========================================================================

  require_once 'php/metier/calendrier.php';
  
  require_once 'php/metier/club.php';
  require_once 'php/bdd/enregistrement_club.php';
  
  require_once 'php/metier/permanence.php';
  require_once 'php/bdd/enregistrement_permanence.php';
  
  require_once 'php/metier/site_activite.php';
  //require_once 'php/bdd/enregistrement_site_activite.php';
  
  require_once 'php/metier/regime_ouverture.php';
  require_once 'php/bdd/enregistrement_regime_ouverture.php';
  
  require_once 'php/metier/support_activite.php';
  require_once 'php/bdd/enregistrement_support_activite.php';
  
  // --------------------------------------------------------------------------
  class Activite_Journaliere {
    
    //private $jour = null; // Instant
    //public function jour() { return $this->jour; }
    
    private $date_jour = null; // DateTimeImmutable
    public function date_jour() { return $this->date_jour; }
    
    public $club = null;
    public $activite_sites = array();

    public $permanence = null;
    
    public $personnes_actives = array(); // cle : code_personne ; valeur : personne
    public $seances_personnes = array();

    public function collecter_informations() {
      $this->collecter_info_club(); // pour le fuseau horaire
      $this->definir_jour(); // apres collecter_info_club car necessite le fuseau horaire
 
      $this->collecter_info_permanence();
      
      $this->collecter_info_sites();  // renseigne les infos pour chaque activite_site
      
      $this->collecter_info_personnes_actives();
      $this->collecter_info_seances_activite(); // renseigne $seances_xxxx, y compris dans activite_sites
    }
    
    protected function collecter_info_club() {
      $code_club = isset($_SESSION['clb']) ? $_SESSION['clb'] : 0;
      $this->club = new Club($code_club);
      $enreg = new Enregistrement_Club();
      $enreg->def_club($this->club);
      $enreg->lire();
    }
    
    protected function definir_jour() {
      $this->date_jour = isset($_GET['j']) ? Calendrier::creer_Instant($_GET['j']): Calendrier::aujourdhui();
    }
    
    protected function collecter_info_permanence() {
      //Permanence::cette_semaine($this->permanence);
      //$cal = Calendrier::obtenir();
      //$this->permanence = new Permanence($cal->numero_semaine($this->jour()), $cal->annee_semaine($this->jour()));
      $sem = $this->date_jour()->format("W");
      //$cal->numero_semaine($this->jour());
      $annee = Calendrier::annee_semaine($this->date_jour());
      $this->permanence = new Permanence($sem, $annee);
      $this->enregistrement_permanence = new Enregistrement_Permanence();
      $this->enregistrement_permanence->def_permanence($this->permanence);
      $this->enregistrement_permanence->lire();
    }
    
    protected function collecter_info_sites() {
      Enregistrement_Site_Activite::collecter("", " code_type ",  $this->sites);
      foreach ($this->sites as $site) {
        $activite_site = new Activite_Site($this, $site);
        $this->activite_sites[$site->code()] = $activite_site;
        $activite_site->collecter_informations();
      }
    }
    
    protected function collecter_info_personnes_actives() {
      return false;
    }

    protected function collecter_info_seances_activite() {
      return false;
    }

  }
  
   // --------------------------------------------------------------------------
  class Activite_Site {
    public $activite_journaliere = null;
    public $site = null;
   
    protected function jour() { return $this->activite_journaliere->date_jour(); }
    protected function latitude() { return $this->site->latitude; }
    protected function longitude() { return $this->site->longitude; }
    
    public $creneaux_activite = array();
    public $fermetures_site = array(); // fermetures incluant le jour
    public $supports_actifs = array(); // dans site ? cle : code_support ; valeur : support activite
    public $indisponibilites_support_jour = array(); // cle : code_support ; valeurs : indispos sur toute la journee
    public $indisponibilites_support_creneaux = array(); // cle : creneau horaire ; valeurs : indispos sopport sur le creneau

    public $seances_creneaux; // cle : creneau horaire ; valeurs : seances programmes
    public $seances_support = array(); // cle : coe_support ; valeurs : seances programmes

    public $marees = array();
    
    public function __construct(Activite_Journaliere $contexte, Site_Activite $site) {
      $this->activite_journaliere = $contexte;
      $this->site = $site;
    }
    
    public function collecter_informations() {
      $this->collecter_info_regime_ouverture();
      $this->definir_creneaux_activite(); // en premier car on a ensuite besoin des creneaux
      //$this->collecter_info_fermetures_site();
      $this->collecter_info_supports_actifs();
      //$this->collecter_info_indispo_supports();
      $this->collecter_info_marees();
    }
    
    protected function collecter_info_regime_ouverture() {
       $this->site->regime_ouverture = Enregistrement_Regime_ouverture::creer($this->site->code_regime_ouverture());
    }
    
    protected function definir_creneaux_activite() {
      $this->creneaux_activite = $this->site->regime_ouverture->definir_creneaux($this->jour(),
                                                                                 $this->latitude(),
                                                                                 $this->longitude());
    }
    
    protected function collecter_info_marees() {
      $this->marees = Enregistrement_Maree::recherche_marees_jour($this->site->code(), $this->jour());
    }
    
    protected function collecter_info_supports_actifs() {
      $supports = null;
      Enregistrement_Support_Activite::collecter("code_site_base = " . $this->site->code() . " AND actif = 1 ", " type DESC, code ASC", $supports);
      $this->site->supports_activite =  $supports;
    }
  }
  
  /*
  class Plan_Journalier_activite extends Activite_Journaliere {
    $fermeture_site = null;
    $indisponibilites_support_jour = array();
    $indisponibilites_support_creneaux = array();
    
  }
   */
  
  // --------------------------------------------------------------------------
  /*
  class Type_Activite {
    public $duree = 60; // minutes
    public $horaire_debut_minutes = 0; // aux heures 'rondes'
    public $decalage_debut_hiver_minutes = 30; // cas AMP pour sorties en mer
    
    public private $code = 0;
    public function code() { return $this->code; }
    public function def_code($valeur) { $this->code = $valeur;}
    
    private $nom = ""; // utf8
    public function nom() { return $this->nom; }
    public function def_nom($valeur) { $this->code = $valeur; }
    
    public function __construct($code) { $this->code = $code; }
  }
  */
  // ==========================================================================
?>
