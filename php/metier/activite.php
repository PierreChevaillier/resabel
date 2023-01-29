<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : Classes pour information et planification activite journaliere
  // copyright (c) 2018-2022 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : 
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  //  - PHP 8.2 sur macOS 13.1 (> 25-dec-2022)
  // --------------------------------------------------------------------------
  // creation : 09-jun-2019 pchevaillier@gmail.com
  // revision : 11-jan-2020 pchevaillier@gmail.com fermeture site et indispo supports
  // revision : 02-mar-2020 pchevaillier@gmail.com collecte infos sur seances
  // revision : 29-dec-2022 pchevaillier@gmail.com fix erreur 8.2
  // --------------------------------------------------------------------------
  // commentaires :
  // - Uniquement 'logique metier': pas d'IHM
  // attention :
  // - En chantier...
  // - non stabilise
  // a faire :
  // - recuperation des donnees $GET ou $POST : a faire ailleurs.
  // ==========================================================================

  require_once 'php/metier/calendrier.php';
  
  require_once 'php/metier/club.php';
  require_once 'php/bdd/enregistrement_club.php';
  
  require_once 'php/metier/membre.php';
  require_once 'php/bdd/enregistrement_membre.php';
  
  require_once 'php/metier/permanence.php';
  require_once 'php/bdd/enregistrement_permanence.php';
  
  require_once 'php/metier/site_activite.php';
  require_once 'php/bdd/enregistrement_site_activite.php';
  
  require_once 'php/metier/regime_ouverture.php';
  require_once 'php/bdd/enregistrement_regime_ouverture.php';
  
  require_once 'php/metier/support_activite.php';
  require_once 'php/bdd/enregistrement_support_activite.php';
  
  require_once 'php/metier/indisponibilite.php';
  require_once 'php/bdd/enregistrement_indisponibilite.php';
  
  require_once 'php/metier/seance_activite.php';
  require_once 'php/bdd/enregistrement_seance_activite.php';
   
  // --------------------------------------------------------------------------
  class Activite_Journaliere {
    
    //private $jour = null; // Instant
    //public function jour() { return $this->jour; }
    
    private $date_jour = null; // DateTimeImmutable
    public function date_jour() { return $this->date_jour; }
    public function def_date_jour(Instant $jour) { $this->date_jour = $jour;}
    
    public $filtre_site = 0;
    public $filtre_type_support = 0;
    public $filtre_support = 0;
    public $debut_plage_horaire = null;
    public $fin_plage_horaire = null;
    
    public $club = null;
    protected $sites = array();
    
    public $activite_sites = array();

    public $permanence = null;
    
    public $personnes_actives = array(); // cle : code_personne ; valeur : personne
    public $seances_personnes = array();

    public function collecter_informations() {
      $this->collecter_info_club(); // pour le fuseau horaire
    
      $this->collecter_info_personnes_actives();
      
      $this->collecter_info_permanence();
      
      $this->collecter_info_sites();  // renseigne les infos pour chaque activite_site
      
     
    }
    
    protected function collecter_info_club() {
      $code_club = isset($_SESSION['clb']) ? $_SESSION['clb'] : 0;
      $this->club = new Club($code_club);
      $enreg = new Enregistrement_Club();
      $enreg->def_club($this->club);
      $enreg->lire();
    }
    
    protected function collecter_info_permanence() {
      //Permanence::cette_semaine($this->permanence);
      //$cal = Calendrier::obtenir();
      //$this->permanence = new Permanence($cal->numero_semaine($this->jour()), $cal->annee_semaine($this->jour()));
      $sem = $this->date_jour()->format("W");
      //$cal->numero_semaine($this->jour());
      $annee = Calendrier::annee_semaine($this->date_jour());
      $this->permanence = new Permanence($sem, $annee);
      $enregistrement_permanence = new Enregistrement_Permanence();
      $enregistrement_permanence->def_permanence($this->permanence);
      $enregistrement_permanence->lire();
    }
    
    protected function collecter_info_sites() {
      Enregistrement_Site_Activite::collecter("", " code_type ", $this->sites);
      foreach ($this->sites as $site) {
        if (($this->filtre_site == 0) || ($site->code() == $this->filtre_site)) {
          $activite_site = new Activite_Site($this, $site);
          $this->activite_sites[$site->code()] = $activite_site;
          $activite_site->collecter_informations();
        }
      }
    }
    
    protected function collecter_info_personnes_actives() {
      $personnes = null;
      $criteres = array();
      //$criteres['act'] = 1;
      Enregistrement_Membre::collecter($criteres, '', '', $personnes);
      $this->personnes_actives = $personnes;
      //echo '>>>>>', count( $this->personnes_actives);
      return false;
    }

    /*
    protected function collecter_info_seances_activite() {
      return false;
    }
*/
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
    public $indisponibilites_support = array(); // cle : code_support ; valeurs : indispos
   
    public $seances_creneaux; // cle : creneau horaire ; valeurs : seances programmes
    public $seances_support = array(); // cle : code_support ; valeurs : listes des seances programmees / creneau horaire
    public $seances_personne = array(); // cle : creneau ; valeurs : codes des personnes participant a une activite sur ce creneau
    
    public $marees = array();
    
    public function __construct(Activite_Journaliere $contexte, Site_Activite $site) {
      $this->activite_journaliere = $contexte;
      $this->site = $site;
    }
    
    public function collecter_informations() {
    
      $this->collecter_info_regime_ouverture();
      $this->definir_creneaux_activite(); // en premier car on a ensuite besoin des creneaux
      $this->collecter_info_fermetures_site();
      $this->collecter_info_supports_actifs();
      $this->collecter_info_indispo_supports();
      $this->collecter_info_seances_activite();
      $this->collecter_info_marees();
    }
    
    protected function collecter_info_fermetures_site() {
      $critere_selection = " date_debut <= '" . $this->jour()->lendemain()->date_sql() . "' AND  date_fin >= '" . $this->jour()->date_sql() . "'";
      Enregistrement_Indisponibilite::collecter($this->site,
                                                2,
                                                $critere_selection,
                                                "",
                                                $this->fermetures_site);
    }
    
    protected function collecter_info_indispo_supports() {
      $critere_selection = " date_debut <= '" . $this->jour()->lendemain()->date_sql() . "' AND  date_fin >= '" . $this->jour()->date_sql() . "'";
      $indispo = array();
      Enregistrement_Indisponibilite::collecter($this->site,
                                                1,
                                                $critere_selection,
                                                "",
                                                $indispo);
      foreach ($indispo as $x) {
        $i = $x->support->code();
        if (!array_key_exists($i, $this->indisponibilites_support)) {
          $this->indisponibilites_support[$i] = array();
        }
        $this->indisponibilites_support[$i][] = $x;
        //echo $i, $x->information();
      }

    }
    
    protected function collecter_info_regime_ouverture() {
       $this->site->regime_ouverture = Enregistrement_Regime_ouverture::creer($this->site->code_regime_ouverture());
    }
    
    protected function definir_creneaux_activite() {
      $creneaux_site = $this->site->regime_ouverture->definir_creneaux($this->jour(),
                                                                      $this->latitude(),
                                                                      $this->longitude());
      foreach ($creneaux_site as $creneau) {
        if ($creneau->debut()->est_apres($this->activite_journaliere->debut_plage_horaire)
             && $creneau->debut()->est_avant($this->activite_journaliere->fin_plage_horaire))
          $this->creneaux_activite[] = $creneau;
      }
     
    }
    
    protected function collecter_info_marees() {
      $this->marees = Enregistrement_Maree::recherche_marees_jour($this->site->code(), $this->jour());
    }
    
    protected function collecter_info_supports_actifs() {
      $supports = null;
      $filtre = "code_site_base = " . $this->site->code() . " AND actif = 1 ";
      if ($this->activite_journaliere->filtre_type_support > 0)
        $filtre = $filtre . " AND support.code_type_support = " . $this->activite_journaliere->filtre_type_support;
      if ($this->activite_journaliere->filtre_support > 0)
        $filtre = $filtre . " AND support.code = " . $this->activite_journaliere->filtre_support;
      
      Enregistrement_Support_Activite::collecter($filtre, " type DESC, code ASC", $supports);
      $this->site->supports_activite =  $supports;
    }
    
    protected function collecter_info_seances_activite() {
      $seances = array();
      $critere_selection = "code_site = " . $this->site->code() . " AND date_debut <= '" . $this->jour()->lendemain()->date_sql() . "' AND date_fin >= '" . $this->jour()->date_sql() . "'";
      $critere_tri =  " code_support ASC, date_debut ASC ";
      Enregistrement_Seance_Activite::collecter($this->site,
                                                $critere_selection,
                                                $critere_tri,
                                                $seances);
      
      // rangement des seances par support et par plage horaire
      $code_support = 0;
      foreach ($seances as $seance) {
        $seance->site = $this->site;
        $i = $seance->support->code();
        if (key_exists($i, $this->site->supports_activite)) {
          $seance->support = $this->site->supports_activite[$i];
          //echo '<p>Support', $seance->support->nom(), '</p>';
          if (!key_exists($i, $this->seances_support))
            $this->seances_support[$i] = array();
        }
        // seances par creneau
        $trouve = false;
        for ($c = 0; ((!$trouve) && ($c < count($this->creneaux_activite))); $c++) {
          //echo '<p>', $c, ' ', $this->creneaux_activite[$c]->debut()->heure_texte(), ' ', $seance->debut()->heure_texte(), '</p>';
          if ($seance->debut()->est_egal($this->creneaux_activite[$c]->debut())) {
            // il y a une seance
            if ($seance->a_un_responsable()) {
              $code_personne = $seance->responsable->code();
              $seance->responsable = $this->activite_journaliere->personnes_actives[$code_personne];
              if (!key_exists($code_personne, $this->seances_personne))
                $this->seances_personne[$code_personne] = array();
              $this->seances_personne[$code_personne][$c] = $seance;
//                $seance->responsable = $this->activite_journaliere->personnes_actives[$seance->responsable->code()];
            }
             foreach ($seance->inscriptions as $participation) {
               $code_personne = $participation->participant->code();
               $participation->participant = $this->activite_journaliere->personnes_actives[$code_personne];
               if (!key_exists($code_personne, $this->seances_personne))
                 $this->seances_personne[$code_personne] = array();
               $this->seances_personne[$code_personne][$c] = $seance;
             }
            $this->seances_support[$i][$c] = $seance;
            $trouve = true;
          }
        }
                
      }
      
      // Definition des informations sur les participants
      //echo 'n actives', count($this->activite_journaliere->personnes_actives);
      /*
      foreach ($seances as $seance) {
        // identite du responsable
        if (!is_null($seance->responsable)) {
          $i = $seance->responsable->code();
          echo $i, ' ';
          $seance->reponsable = $this->activite_journaliere->personnes_actives[$i];
        }
      }
       */
      //echo '<p>', count($this->seances_support), '</p>', PHP_EOL;
    }
    
    public function site_ferme() {
      $d = $this->creneaux_activite[0]->debut();
      $f = $this->creneaux_activite[count( $this->creneaux_activite)-1]->fin();
      $creneau_activite = new Intervalle_temporel($d, $f);
      $condition = false;
      foreach ($this->fermetures_site as $ferm) {
        $creneau_fermeture = new Intervalle_temporel($ferm->debut, $ferm->fin);
        $condition = $creneau_fermeture->couvre($creneau_activite);
        if ($condition) break;
      }
      return $condition;
    }
    
    public function site_ferme_creneau(Instant $debut, Instant $fin) {
      $creneau = new Intervalle_temporel($debut, $fin);
      $condition = false;
      foreach ($this->fermetures_site as $ferm) {
        $creneau_fermeture = new Intervalle_temporel($ferm->debut, $ferm->fin);
        $condition = $creneau->chevauche($creneau_fermeture);
        if ($condition) break;
      }
      return $condition;
    }
    
    public function support_indisponible_creneau(Support_Activite $support, Instant $debut, Instant $fin) {
      $condition = array_key_exists($support->code(), $this->indisponibilites_support);
      if ($condition) {
        $creneau = new Intervalle_temporel($debut, $fin);
        $indispos = $this->indisponibilites_support[$support->code()];
        foreach ($indispos as $indispo) {
          $creneau_indispo = new Intervalle_temporel($indispo->debut, $indispo->fin);
          $condition = $creneau->chevauche($creneau_indispo);
          if ($condition) break;
        }
      }
      return $condition;
    }
    
    
    public function seance_programmee($code_support, $index_creneau) {
      $seance = null;
      if (key_exists($code_support, $this->seances_support) && key_exists($index_creneau, $this->seances_support[$code_support]))
        $seance = $this->seances_support[$code_support][$index_creneau];
      return $seance;
    }
    
    public function participe_activite_creneau(Membre $personne, Intervalle_Temporel $creneau) {
      $code_personne = $personne->code();
      if (array_key_exists($code_personne, $this->seances_personne)) {
        foreach ($this->seances_personne[$code_personne] as $seance) {
          if ($seance->debut()->est_egal($creneau->debut())) {
            return true;
          }
        }
      }
      return false;
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
