<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classes definissant les 'vues' d'un objet de la classe
  //               Seance_activite
  //               generation du code html pour affichage des informations
  //               sur une seance d'activite
  // copyright (c) 2018-2024 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
// dependances :
//  - bootstrap 5.3
//  - valeur variables $_SESSION
//  - code actions
//  - actions_seance_activite.js
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  //  - PHP 8.2 sur macOS 13.1 (> 25-dec-2022)
  // --------------------------------------------------------------------------
  // creation : 22-jan-2020 pchevaillier@gmail.com
  // revision : 25-aug-2020 pchevaillier@gmail.com contexte actions + des actions
  // revision : 29-dec-2022 pchevaillier@gmail.com fix erreur 8.2 : utf8_encode deprecated
  // revision : 17-feb-2023 pchevaillier@gmail.com + changement horaire
// revision: 04-jul-2024 pchevaillier@gmail.com + affichage photo support activite
// revision: 13-jul-2024 pchevaillier@gmail.com + couleur / seance passee et indispo
  // --------------------------------------------------------------------------
  // commentaires :
  // - en evolution
  // - s'inspirer de resabel V1 (sortie_presentations.php)
  //   donc pas 1 Element ?
  // attention :
  // - experimental
  // a faire : Afficheur simple : sans controle et Afficheur avec controle(s)
  //  - afficher champ information pour seance et participation
  // ==========================================================================
  
  // --------------------------------------------------------------------------
  require_once 'php/metier/seance_activite.php';
  require_once 'php/metier/personne.php';
  require_once 'php/metier/calendrier.php';
  require_once 'php/metier/permanence.php';
  
  require_once 'php/elements_page/generiques/element.php';
    
  // --------------------------------------------------------------------------
  class Contexte_Action_Seance {
    public $page = null;
    public $session_admin = false;
    public $session_pers = false;
    public $session_club = false;
    public $membre_actif = false;
    public $responsable = false;
    public $utilisateur = null;
    private $action = '';
    public function code_action() { return $this->action; }
    private $parametres = array();
    
    public $session_interactive = false;
    public function __construct($page) {
      $this->page = $page;
    }
    
    public function initialiser() {
      $this->session_admin = isset($_SESSION['adm']) && $_SESSION['adm'];
      $this->session_pers = isset($_SESSION['prs']) && $_SESSION['prs'];
      $this->session_club = ! $this->session_pers;
      $this->membre_actif = $this->session_pers && isset($_SESSION['usr']) && isset($_SESSION['act']);
      $this->responsable = $this->session_pers && isset($_SESSION['usr']) && isset($_SESSION['cdb']) && $_SESSION['cdb'];
      if ($this->session_pers) {
        $this->utilisateur = new Membre($_SESSION['usr']);
      }
      if (isset($_GET['a'])) $this->action = $_GET['a'];

      //if ($this->inscription_individuelle())
      $this->page->ajouter_script("js/requete_inscription_individuelle.js");
      $this->page->ajouter_script("js/actions_seance_activite.js");
      $this->page->ajouter_script("js/afficher_infos_seance_activite.js");
      $this->page->ajouter_script("js/requete_info_personne.js");
      
      // test si session permet de faire des actions sur les seances d'activite
      // TODO: logique a revoir/affiner
      $this->session_interactive = ($this->session_club || $this->session_admin || ($this->action == 'ie'));
      if ($this->action == 'l') $this->session_interactive = false; // TODO: pour voir...
      if (!$this->session_interactive && $this->session_pers) {
        $permanence = null;
        Permanence::cette_semaine($permanence);
        $enregistrement_permanence = new Enregistrement_Permanence();
        $enregistrement_permanence->def_permanence($permanence);
        $utilisateur_de_permanence = false;
        if ($enregistrement_permanence->lire())
          $utilisateur_de_permanence = ($permanence->code_responsable() == $this->utilisateur->code());
        $this->session_interactive = $utilisateur_de_permanence;
      }
    }
    
    public function inscription_individuelle() {
      return ($this->session_pers && ($this->action == 'ii'));
    }
    
    public function utilisateur_responsable() {
      return ($this->responsable);
    }
    
  }
    
  // --------------------------------------------------------------------------
  abstract class Afficheur_Seance_Activite extends Element {
    
    public $est_interactif = true;
    protected $site_ouvert = true;
    protected $support_disponible = true;
    
    static function creer(Page $page, Seance $objet_metier) {
      $vue = null;
      $this->seance = $seance;
      /*
      if (is_a($objet_metier, 'Regime_Diurne'))
        $vue = new Afficheur_Regime_Diurne($page);
      elseif (is_a($objet_metier, 'Regime_Hebdomadaire'))
        $vue = new Afficheur_Regime_Hebdomadaire($page);
      $vue->def_regime($objet_metier);
       */
      return $vue;
    }
    
    public $activite_site = null;
    
    protected $seance = null;
    public function def_seance(Seance_Activite $objet_metier) {
      $this->seance = $objet_metier;
    }
    public function seance() { return $this->seance; }
    
    public function contexte_action() { return $this->page()->contexte_action(); }
    
    protected $id_dialogue_action = ""; // id de la fenetre de dialogue (modale)
    
    protected $params_action_seance = "";
    
    protected $largeur = 200;
    protected $couleur_fond_resp = "#FFE066";
    protected $couleur_texte_resp = "Black";
    
    protected $couleur_texte_cdb = "Red";
    
    protected $couleur_fond_equipier = "Cornsilk";
    protected $couleur_texte_equipier = "Black";
    
    protected $couleur_fond_debutant = "LightCyan"; //"#136BAC";
    protected $couleur_texte_debutant = "DarkSlateBlue"; //white";
    
    protected $couleur_fond_place_libre = " #DAF7A6"; //LightCyan";
    protected $couleur_texte_place_libre = "Black";
    
    protected $pas_encore_controle_vide = true;
    
    public function __construct(Page $page,
                                Seance_Activite $seance,
                                ?Activite_site $activite_site) {
      $this->def_page($page);
      $this->seance = $seance;
      $this->activite_site = $activite_site;
      
      if (! is_null($this->activite_site)) {
        $this->site_ouvert = !$this->activite_site->site_ferme_creneau($this->seance->debut(),
                                                                     $this->seance->fin());
        $this->support_disponible = !$this->activite_site->support_indisponible_creneau($this->seance->support,
                                                                                       $this->seance->debut(),
                                                                                       $this->seance->fin());
      }
      
      $this->id_dialogue_action = "aff_act_" . $this->seance->site->code(); //$this->activite_site->site->code();
      $this->params_action_seance = '\'' . $this->id_dialogue_action . '\', '
      . $this->seance->code() . ', '
      . $this->seance->site->code() . ', '
      . $this->seance->code_support() . ', '
      . '\'' . $this->seance->debut()->date_heure_sql() . '\', '
      . '\'' . $this->seance->fin()->date_heure_sql() . '\'';
    }
    
    public function initialiser() { }
    
    public function generer_id(int $rang): string {
      return $this->seance->support->code() . '_' . $this->seance->debut()->date_heure_sql() . '_' . $rang;
    }
    
    protected function afficher_debut() {
      echo "\n<div style=\"padding:4px\">\n";
      //echo '<div class="row h-100 justify-content-center align-items-center">';
    }
    
    protected function afficher_fin() {
      echo "</div>\n";
    }
    
    abstract function formater();
    
    protected function formater_participant(Participation_Activite $p,
                                            int $rang,
                                            String & $code_html): bool {
      $personne = $p->participant;
      if (($this->seance->a_un_responsable()) && ($personne->code() == $this->seance->responsable->code()))
        return false;
      
      $couleur_texte = ($personne->est_debutant()) ?  $this->couleur_texte_debutant :  $this->couleur_texte_equipier;
      $couleur_texte = ($personne->est_chef_de_bord()) ?  $this->couleur_texte_cdb :  $couleur_texte;
      $couleur_fond = ($personne->est_debutant()) ? $this->couleur_fond_debutant :  $this->couleur_fond_equipier;

      $str_participant = ""; //utf8_encode(''); //$personne->prenom . " " . $personne->nom;
      $code_interacteur = ""; //utf8_encode('');

      $id = $this->generer_id($rang);

      if ($this->est_interactif) {
        $code_interacteur = $code_interacteur . $this->generer_code_interacteur($personne);
      } else {
         $str_participant = '<span id="equip_' . $id . '">'
          . $personne->prenom . ' ' . $personne->nom . '</span>';
      }
      $code_html = $code_html . "<div id=\"" . $id . "\" style =\"width:" . $this->largeur . "px;color:" . $couleur_texte . ";background-color:" . $couleur_fond . ";text-align:center;padding:1px\"><div style=\"min-height:31px\">" . $str_participant . ' ' . $code_interacteur . "</div></div>";
      return true;
    }
    
    protected function generer_code_interacteur(Membre $participant): string {
      $code = ""; //utf8_encode('');
      $code = $code . '<div class="dropdown">';
       
      // --- le menu pour interagir : effectuer une action sur la participation a l'activite
      $couleur_texte = ($participant->est_debutant()) ?  $this->couleur_texte_debutant :  $this->couleur_texte_equipier;
      $couleur_texte = ($this->seance->responsable_requis() && !$this->seance->a_un_responsable() && $participant->est_chef_de_bord()) ?  $this->couleur_texte_cdb :  $couleur_texte;
      $couleur_fond = ($participant->est_debutant()) ? $this->couleur_fond_debutant :  $this->couleur_fond_equipier;
      
      $texte_bouton = '<span style="color:' . $couleur_texte
        . ';background-color:' . $couleur_fond
        .';">';
      $texte_bouton = $texte_bouton . substr($participant->prenom . ' ' . $participant->nom, 0, 22);
      $texte_bouton = $texte_bouton . '</span>';
      $id_menu = 'mnu_particip_' . $this->seance->support->code() . '_' . $this->seance->debut()->date_heure_sql() . '_' . $participant->code();
      $code = $code . '<button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" style="min-width:196px;" id="' . $id_menu . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . $texte_bouton . '</button>';
       
      $code = $code . '<div class="dropdown-menu" aria-labelledby="' . $id_menu . '">';
      
      // --- Actions du menu toujours possibles
      $menu = "";
      $params = $participant->code() . ', \'' . $this->id_dialogue_action . '\'';
      $menu = $menu . '<a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#' . $this->id_dialogue_action . '" onclick="requete_info_personne(' . $params . '); return false;">Afficher infos</a>';
      
      // --- Actions dependant du contexte
      $dispo = $this->site_ouvert && $this->support_disponible;
      
      $resp = $this->seance->a_comme_responsable($participant) ? 1:0;
      $action_autorisee = $this->contexte_action()->session_interactive;
      
      if ($this->contexte_action()->session_pers) {
        $utilisateur_participant = ($this->seance->a_comme_participant($this->contexte_action()->utilisateur));
        $action_autorisee = $this->contexte_action()->session_interactive || $participant->code() == $this->contexte_action()->utilisateur->code() || $utilisateur_participant;
      }
      // Desinscription. Possible si :
      // - session interactive
      // - OU (le participant est l'utilisateur ou l'utilisateur est un des participants
      $desinscription_possible = $action_autorisee;
      if ($desinscription_possible) {
        $params = $this->params_action_seance . ', ' . $participant->code() . ', ' . $resp;
        $code_action = "di";
        $params = $params . ', \'' . $code_action . '\'';
        $menu = $menu . '<a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#' . $this->id_dialogue_action . '" onclick="requete_inscription_individuelle(' . $params . ');return false;">Annuler participation</a>';
      }
      
      // Passage du role responsable au role equipier. Possible si
      // - site ouvert et support disponible
      // - action autorisee
      // - ET le participant est le responsable
      // - ET il y a une place libre
      $place_libre_equipier = $this->seance->nombre_places_disponibles() > ($this->seance->a_un_responsable() ? 0:1);
      $passage_possible = $dispo && $action_autorisee && $resp && $place_libre_equipier;
      if ($passage_possible) {
        $params = $this->seance->code . ', ' . $participant->code();
        $code_action = "mre"; // Modification : passage Responsable a Equipier
        $params = $params . ', \'' . $code_action . '\'';
        $menu = $menu . '<a class="dropdown-item" onclick="requete_changement_role_seance(' . $params . ');return false;">Passer équipier</a>';
      }

      // Passage du role equipier au role responsable. Possible si :
      // - site ouvert et support disponible
      // - action autorisee
      // - le participant est un equipier
      // - le type d'activite requiert un responsable participant
      // - il n'y a pas de responsable
      // - le participant est qualifie (chef de bord)
      $possible = $this->seance->responsable_requis() && !$this->seance->a_un_responsable() && $participant->est_chef_de_bord() && !$this->seance->a_comme_responsable($participant);
      $passage_possible = $dispo && $action_autorisee && $possible;
      if ($passage_possible) {
        $params = $this->seance->code . ', ' . $participant->code();
        $code_action = "mer"; // Modification : passage Equipier a Responsable
        $params = $params . ', \'' . $code_action . '\'';
        $menu = $menu . '<a class="dropdown-item" onclick="requete_changement_role_seance(' . $params . ');return false;">Passer Chef de bord</a>';

      }
      $code = $code . $menu . '</div></div>';
      return $code;
    }
    
    protected function formater_responsable(String & $code_html) {
      $str_resp = "";
      $code_interacteur = "";
      if ($this->seance->a_un_responsable()) {
        //$str_resp = $this->seance->responsable->prenom . " " . $this->seance->responsable->nom;
        if ($this->est_interactif) {
          $code_interacteur = $code_interacteur . $this->generer_code_interacteur($this->seance->responsable);
        } else {
          // pas d'interaction : on affichera juste l'identite du responsable
          $str_resp = $this->seance->responsable->prenom . " " . $this->seance->responsable->nom;
        }
      } else {
        $str_resp = "&nbsp;";
        // pas de responsable : on offre la possibilite d'en inscrire 1 (en mode interactif)
        if ($this->est_interactif) {
          $creneau = $this->seance->plage_horaire;
          $inscription_possible = $this->contexte_action()->inscription_individuelle()
            && !$this->activite_site->participe_activite_creneau($this->contexte_action()->utilisateur, $this->seance->plage_horaire)
            && $this->contexte_action()->utilisateur_responsable();
          $indispo = ($this->activite_site->site_ferme_creneau($creneau->debut(), $creneau->fin()) || $this->activite_site->support_indisponible_creneau($this->seance->support, $creneau->debut(), $creneau->fin()));
          $inscription_possible = $inscription_possible && !$indispo;
          
          if ($inscription_possible) {
            // Cas d'uns inscription individuelle
            $params = $this->params_action_seance . ', ' . $this->contexte_action()->utilisateur->code() . ', 1'; // 1: participation en tant que responsable (chef de bord)
            $params = $params . ', \'' . $this->contexte_action()->code_action() . '\'';
            $code_interacteur = '<img src="../../assets/icons/pencil-square.svg" alt="+" width="24" height="24" class="rsbl-tooltip" data-bs-toggle="modal" data-bs-target="#' . $this->id_dialogue_action . '" title="Inscription chef de bord" onclick="requete_inscription_individuelle(' . $params . ');">';
          }
        }
      }
      $id = $this->generer_id(0);
      $str_resp = '<span id="resp_' . $id . '"> ' . $str_resp . '</span> ';
      $dispo = $this->site_ouvert && $this->support_disponible;
      $couleur_fond = ($dispo) ? $this->couleur_fond_resp : 'Gainsboro';
      
      $code_html = $code_html . "<div id=\"" . $id . "\" style =\"width:" . $this->largeur . "px;color:" . $this->couleur_texte_resp . ";background-color:" . $couleur_fond . ";text-align:center;padding:1px\"><div style=\"min-height:31px\">" . $str_resp . $code_interacteur . "</div></div>";
      
      //$code_html = $code_html . "<td width=\"" . $this->largeur . "px\" bgcolor=\"" . $this->couleur_fond_resp . "\" align=\"center\">" . $str_resp . "</td>";
      return;
    }

    protected function formater_place_libre(int $rang, String & $code_html) {
      $id = $this->generer_id($rang);
      $dispo = $this->site_ouvert && $this->support_disponible;
      $couleur_fond = ($dispo) ? $this->couleur_fond_place_libre : 'GhostWhite';
      $code_html = $code_html . "<div id=\"" . $id . "\" style =\"width:" . $this->largeur . "px;min-height:31px;color:" . $this->couleur_texte_place_libre . ";background-color:" . $couleur_fond . ";text-align:center;padding:1px\"><div style=\"min-height:31px\">";
      $code_html = $code_html . '<span id="equip_' . $id . '"> </span>';
      $code_interacteur = ""; //utf8_encode('');
      if ($this->est_interactif) {
        $inscription_possible = $this->contexte_action()->inscription_individuelle()
          && !$this->activite_site->participe_activite_creneau($this->contexte_action()->utilisateur, $this->seance->plage_horaire);
        $creneau = $this->seance->plage_horaire;
        $indispo = ($this->activite_site->site_ferme_creneau($creneau->debut(), $creneau->fin()) || $this->activite_site->support_indisponible_creneau($this->seance->support, $creneau->debut(), $creneau->fin()));
        $inscription_possible =  $inscription_possible && !$indispo;
        
        $code_interacteur = ""; //utf8_encode('');
        if ($inscription_possible && $this->pas_encore_controle_vide) {
          /*
          $params = '\'' . $this->id_dialogue_action . '\', '
                    . $this->seance->code() . ', '
                    . $this->activite_site->site->code() . ', '
                    . $this->seance->code_support() . ', '
                    . '\'' . $this->seance->debut()->date_heure_sql() . '\', '
                    . '\'' . $this->seance->fin()->date_heure_sql() . '\'';
           */
          $params = $this->params_action_seance . ', ' . $this->contexte_action()->utilisateur->code() . ', 0'; // 0 : participation pas en tant que responsable (chef de bord)
          $params = $params . ', \'' . $this->contexte_action()->code_action() . '\'';
          $code_interacteur = '<img src="../../assets/icons/pencil-square.svg" alt="inscrire" width="24" height="24" class="rsbl-tooltip" data-bs-toggle="modal" data-bs-target="#' . $this->id_dialogue_action . '" title="Inscription équipier" onclick="requete_inscription_individuelle(' . $params . ');">';
          
          $this->pas_encore_controle_vide = false;
        }
      }
      $code_html = $code_html . $code_interacteur . "</div></div>";
      return;
    }
    
  }
  
  // --------------------------------------------------------------------------
   class Afficheur_Vertical_Seance extends Afficheur_Seance_Activite {
     
     public function formater() {
       //$this->afficher_debut();
       $code_html = ""; //utf8_encode('');
       $code_html = $code_html . "\n<div style=\"padding:2px;\">\n";
       //$code_html = $code_html . "\n\t<table class=\"table table-bordered table-condensed\" style=\"width:"
       //             . ($this->largeur + 0) . "px;\"><tbody>\n";
    
       // le responsable
       if ($this->seance->responsable_requis()) {
         //$code_html = $code_html . "\t\t<tr>";
         $this->formater_responsable($code_html);
         //$code_html = $code_html . "\t\t</tr>\n";
       }
       
       // les equipiers
       $rang = 1;
       $ajout = false;
       foreach ($this->seance->inscriptions as $participation) {
         //$code_html = $code_html . "<tr>";
         $ajout = $this->formater_participant($participation, $rang, $code_html);
         //$code_html = $code_html . "</tr>\n";
         if ($ajout) $rang++;
       }
    
       // les places vides
       $n_vides = $this->seance->nombre_places_disponibles();
       if ($this->seance->responsable_requis() && !$this->seance->a_un_responsable())
         $n_vides -= 1;
       
       for ($v = 0; $v < $n_vides; $v++) {
         //$code_html = $code_html .  "<tr>";
         $this->formater_place_libre($rang, $code_html);
         //$code_html = $code_html . "</tr>\n";
         $rang++;
       }
    
       //$code_html = $code_html . "\t</tbody></table>\n";
       $code_html = $code_html . "</div>\n";
       return $code_html;
       
     }
     
     protected function afficher_corps() {
       $code_html = '';
       echo $code_html;
       return;
     }
        
   }

  // --------------------------------------------------------------------------
  class Controleur_Action_Seance {
    private $index_creneau = -1; // necessaire pour parler du creneau avant ou apres
    public $seance = null; // objet metier
    public $page = null; // pour ajouter des javascripts
    public $activite_site = null; // contexte : activite du site sur la periode
    protected $afficheur = null;
    protected $id_dialogue_action = "";
    
    protected $site_ouvert = true;
    protected $support_disponible = true;
    
    protected function contexte(): ?Contexte_Action_Seance {
      return $this->afficheur->contexte_action();
    }
    
    public function __construct(Afficheur_Seance_Activite $afficheur, int $index_creneau) {
      $this->index_creneau = $index_creneau;
      $this->afficheur = $afficheur;
      $this->page = $afficheur->page();
      $this->seance = $afficheur->seance();
      $this->activite_site = $afficheur->activite_site;
      
      $this->site_ouvert = !$this->activite_site->site_ferme_creneau($this->seance->debut(),
                                                                     $this->seance->fin());
      $this->support_disponible = !$this->activite_site->support_indisponible_creneau($this->seance->support,
                                                                                       $this->seance->debut(),
                                                                                       $this->seance->fin());
      
      $this->id_dialogue_action = "aff_act_" . $this->activite_site->site->code();
    }
    
    // Regles 'metier' definissant les actions possibles
    
    protected function possible_contacter_participants() : bool {
      return $this->contexte()->session_pers;
    }
    
    protected function possible_inscrire_responsable(): bool {
      $action = $this->contexte()->code_action();
      if ($action == 'l') return false;
      
      if (!$this->seance->responsable_requis()) return false;
      $pas_de_place = $this->seance->a_un_responsable();
      if ($pas_de_place) return false;
      
      $activite_possible = $this->site_ouvert && $this->support_disponible;
      if (!$activite_possible) return false;
      
      $profil_utilisateur = $this->contexte()->session_admin || $this->contexte()->session_club || $this->contexte()->membre_actif;
      if (!$profil_utilisateur) return false;
      
      $est_participant = $this->contexte()->membre_actif && $this->seance->a_comme_participant($this->contexte()->utilisateur);
      
      $action_possible = (($action == 'ie') || (($action == 'ii') && $est_participant));
      return $action_possible;
    }

    protected function possible_inscrire_equipier(): bool {
      
      $action = $this->contexte()->code_action();
      if ($action == 'l') return false;

      $pas_de_place = $this->seance->nombre_places_est_limite() && $this->seance->nombre_places_equipiers_disponibles() == 0;
      if ($pas_de_place) return false;
      
      $activite_possible = $this->site_ouvert && $this->support_disponible;
      if (!$activite_possible) return false;
      
      $profil_utilisateur = $this->contexte()->session_admin || $this->contexte()->session_club || $this->contexte()->membre_actif;
      if (!$profil_utilisateur) return false;
      
      $est_participant = $this->contexte()->membre_actif && $this->seance->a_comme_participant($this->contexte()->utilisateur);
      
      $action_possible = (($action == 'ie') || (($action == 'ii') && $est_participant));
      return $action_possible;
    }
    
    protected function possible_inscrire_equipage(): bool {
      $possible = $this->possible_inscrire_responsable() || $this->possible_inscrire_equipier();
      return $possible;
    }

    protected function possible_annuler_seance(): bool {

      $action = $this->contexte()->code_action();
      if ($action == 'l') return false;

      if ($this->seance->nombre_participants()  == 0) return false;
            
      $profil_utilisateur = $this->contexte()->session_admin || $this->contexte()->session_club || $this->contexte()->membre_actif;
      if (!$profil_utilisateur) return false;
      
      $est_participant = $this->contexte()->membre_actif && $this->seance->a_comme_participant($this->contexte()->utilisateur);
      
      $action_possible = (($action == 'ie') || (($action == 'ii') && $est_participant));
      return $action_possible;
    }
    
    protected function possible_changer_creneau(): bool {
      return $this->possible_annuler_seance();
    }
    
    protected function possible_changer_support(): bool {
      $autres_support = count($this->activite_site->site->supports_activite) > 1;
      return ($autres_support && $this->possible_annuler_seance());
    }
    
    protected function formater_info_seance() {
      $code = ""; //utf8_encode('');
      $code = $code . $this->seance->debut()->date_texte();
      $code = $code . ' de ' . $this->seance->debut()->heure_texte();
      $code = $code . ' à ' . $this->seance->fin()->heure_texte();
      $support = $this->seance->support->nom();
      if (is_a($this->seance->support, 'Bateau'))
        $support = $support . ' (' . $this->seance->support->numero() . ')';
      $code = $code . '<br />sur ' . $support;
      $code = $code . ', ' . $this->activite_site->site->nom();
      return $code;
    }
    
    protected function formater_info_participations() {
      $code = "";
      $presentation_nom = new Afficheur_Nom();
      $presentation_tel = new Afficheur_telephone();
      $code = $code . '<div class="container"><table class="table table-sm"><tbody>';
      foreach ($this->seance->inscriptions as $participation) {
        $p = $participation->participant;
        $presentation_nom->def_personne($p);
        $code = $code . '<tr><td>' . $presentation_nom->formatter() . '</td>'
          . '<td>' . $presentation_tel->formatter($p->telephone) . '</td>'
          . '</tr>';
      }
      $code = $code . '</tbody></table></div>';
      return $code;
    }
    
    protected function formater_mail_participants() {
      $code = "";
      $code = $code . 'mailto:';
      foreach ($this->seance->inscriptions as $participation) {
        $p = $participation->participant;
        if (strlen($p->courriel) > 0)
          $code = $code . ',' . $p->courriel;
      }
      $code = $code . '?Subject=AMP%20:%20annulation séance du ' . $this->seance->debut()->date_texte();
      $code = $code . ' de ' . $this->seance->debut()->heure_texte();
      $code = $code . ' à ' . $this->seance->fin()->heure_texte();

      return $code;
    }
    
    protected function formater_bouton_menu_action_seance(): string  {
      $code_html = '<div class="dropdown">';
      
      $texte_bouton = '';
      if (is_a($this->seance->support, 'Bateau'))
        $code_support = $this->seance->support->numero();
      else
        $code_support = $this->seance->support->nom();
      $texte_bouton = $texte_bouton . $code_support
        . ' à ' . $this->seance->debut()->heure_texte();
      
      $id_menu = 'mnu_seance_' . $this->seance->support->code() . '_' . $this->seance->debut()->date_heure_sql();
      $sous_classe = 'btn-outline-primary';
      $activite_possible = $this->site_ouvert && $this->support_disponible;
      if (!$activite_possible)
        $sous_classe = 'btn-dark';
      else {
        if ($this->seance->debut() < Calendrier::maintenant())
          $sous_classe = 'btn-outline-warning';
      }
      $code_html = $code_html
        . '<button class="btn ' . $sous_classe . ' btn-sm dropdown-toggle" type="button" id="' . $id_menu
        . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
        . $texte_bouton . '</button>';
      $code_html = $code_html . '<div class="dropdown-menu" aria-labelledby="' . $id_menu . '">';
      return $code_html;
    }
    
    public function formater_menu_action() {
      // parametres communs a tous les scripts agissant sur la seance
      $params_seance = $this->seance->code() . ', '
                      . $this->activite_site->site->code() . ', '
                      . $this->seance->code_support() . ', '
                      . '\'' . $this->seance->debut()->date_heure_sql() . '\', '
                      . '\'' . $this->seance->fin()->date_heure_sql() . '\'';

      $code = '';
      // --- le bouton pour interagir
      $code = $code . $this->formater_bouton_menu_action_seance();
     
      // --- Actions du menu toujours possibles
      $menu = "";
      
      // copie de page_accueil_perso.php
      $details = ""; //utf8_encode("");
      $seance = $this->seance;
      $aff_tel = new Afficheur_Telephone();
      $aff_mail = new Afficheur_Courriel_Actif();
      
      $entete = "";
      if ($this->seance->debut() < Calendrier::maintenant())
        $entete = $entete . '<span class="bg-warning">séance passée</span> <br />';
      $entete =  $entete . $seance->debut()->date_texte_court()
        . " " . $seance->debut()->heure_texte() . " - " . $seance->fin()->heure_texte()
        . "<br />" . $seance->site->nom();
      
      $sujet = "Sortie du " . $seance->debut()->date_texte()
             . " à " . $seance->debut()->heure_texte();
      $info_support = " sur " . $seance->support->nom();
      if (is_a($seance->support, "Bateau"))
        $info_support = $info_support . " (" . $seance->support->numero() . ")";
      $sujet = $sujet . " " . $info_support;
      $sujet = $sujet . " - " . $seance->site->nom();
      $entete = $entete . $info_support;
      if (strlen($seance->support->nom_fichier_image()) > 0) {
          $chemin_fichier_image = '../photos/supports_activite/' . $seance->support->nom_fichier_image();
          $entete  = $entete . '<img src="' . $chemin_fichier_image . '" alt="' . $seance->support->nom_fichier_image() . '" width=256>';
        }
      foreach ($seance->inscriptions as $participation) {
        $p = $participation->participant;
        $details = $details . $p->prenom() . " " . $p->nom();
        $details = $details . " " . $aff_tel->formatter($p->telephone);
        $aff_mail->def_personne($p);
        $details = $details . " " . $aff_mail->formatter("Je te contacte ", $sujet);
        $details = $details . "<br />";
      }
      $entete = htmlspecialchars($entete); // indispensable car il y a des " dans $entete
      $details = htmlspecialchars($details); // idem pour $details
      $modal_id = "aff_act";
      $menu = $menu . '<a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#' . $this->id_dialogue_action . '" onclick="return afficher_info_seance(\'' . $this->id_dialogue_action . '\', \''
          . $entete . '\', \'' . $details . '\');">Afficher informations</a>';
      
      // --- Actions dependant du contexte
      if ($this->possible_inscrire_responsable()) {
        $rang = 0;
        $id_participation = $this->afficheur->generer_id($rang);
        $params = $params_seance . ', \'' . $id_participation . '\', ' . $rang;
        $menu = $menu . '<a class="dropdown-item" onclick="activer_formulaire(' . $params . '); return false; " >Inscrire Chef de bord</a>';
      }
      
      if ($this->possible_inscrire_equipier()) {
        $rang = $this->seance->nombre_participants() + ($this->seance->a_un_responsable() ? 0:1);
        $id_participation = $this->afficheur->generer_id($rang);
        $params = $params_seance . ', \'' . $id_participation . '\', ' . $rang;
        $menu = $menu . '<a class="dropdown-item" onclick="activer_formulaire(' . $params . '); return false;" >Inscrire équipier</a>';
      }
      
      if ($this->possible_inscrire_equipage()) {
        // url retour = activites.php?a=ie&j=2024-01-27&sa=1&pc=PT09H30M&dc=PT10H30M&ts=0&s=0
        // cf. page_activites.php
        // ce qu'il faut pour pour revenir sur la page courante (selection pour recherche dispo)
        $code_param_url = "?a=ie&j=" . $_GET['j']
          . "&sa=" . $this->activite_site->site->code()
          . "&pc=" . $_GET['pc']
          . "&dc=" . $_GET['dc']
          . "&ts=" . $_GET['ts']
          . "&s=" . $_GET['s'];
        // et ce qu'il faut pour savoir sur quelle seance on agit
        $code_param_url = $code_param_url
          . "&seance=" . $this->seance->code() /* egal zero si pas de seance */
        . "&support=" . $this->seance->code_support()
        . "&hd=" . $this->seance->debut()->valeur_cle_horaire()
        . "&hf=" . $this->seance->fin()->valeur_cle_horaire()
        ;
        $menu = $menu
          . '<a class="dropdown-item" href ="inscription_equipage.php'
          . $code_param_url
          .  '" >Inscrire équipage</a>';
      }
      
      if ($this->seance->nombre_participants() > 0) {
        $html_info_seance = htmlspecialchars($this->formater_info_seance());
        $html_info_participations = htmlspecialchars($this->formater_info_participations());
        $params = $this->seance->code()
          . ', \'' . $this->id_dialogue_action
          . '\', \'' . $html_info_seance
          . '\', \'' . $html_info_participations
          . '\', \'' . htmlspecialchars($this->formater_mail_participants())
          . '\'';
        
        if ($this->possible_annuler_seance()) {
          $menu = $menu . '<a class="dropdown-item" onclick="activer_controle_annulation_seance(' . $params . '); return false;" data-bs-toggle="modal" data-bs-target="#' . $this->id_dialogue_action . '">Annuler séance</a>';
        }
        
        if ($this->possible_changer_creneau()) {
          $code_support = $this->seance->code_support();
          $nouveau_creneau = null;
          if ($this->afficheur->activite_site->creneau_precedent_est_libre($code_support,
                                                                         $this->index_creneau)) {
            $nouveau_creneau = $this->afficheur->activite_site->creneaux_activite[$this->index_creneau - 1];
            if ($this->afficheur->activite_site->personne_participe_activite_creneau($this->seance, $nouveau_creneau)) {
              $params_nouveau_creneau = $params  . ', '
                . '\'' . $nouveau_creneau->debut()->date_heure_sql() . '\', '
                . '\'' . $nouveau_creneau->fin()->date_heure_sql() . '\'';
              $menu = $menu . '<a class="dropdown-item" onclick="activer_controle_changer_horaire_seance('
                . $params_nouveau_creneau . '); return false;" data-bs-toggle="modal" data-bs-target="#' . $this->id_dialogue_action . '">Passer sur créneau précédent</a>';
            }
          }
        if ($this->afficheur->activite_site->creneau_suivant_est_libre($code_support,
                                                                       $this->index_creneau)) {
          $nouveau_creneau = $this->afficheur->activite_site->creneaux_activite[$this->index_creneau + 1];
          if ($this->afficheur->activite_site->personne_participe_activite_creneau($this->seance, $nouveau_creneau)) {
            $params_nouveau_creneau = $params  . ', '
              . '\'' . $nouveau_creneau->debut()->date_heure_sql() . '\', '
              . '\'' . $nouveau_creneau->fin()->date_heure_sql() . '\'';
            $menu = $menu . '<a class="dropdown-item" onclick="activer_controle_changer_horaire_seance('
              . $params_nouveau_creneau . '); return false;" data-bs-toggle="modal" data-bs-target="#' . $this->id_dialogue_action . '">Passer sur créneau suivant</a>';
          }
        }
      }
        
        if ($this->possible_changer_support()) {
        $params = $params_seance . ', \'' . $this->id_dialogue_action . '\', \'' . $html_info_seance . '\', \'' . $html_info_participations . '\', \'' . htmlspecialchars($this->formater_mail_participants()) . '\'';
        $menu = $menu . '<a class="dropdown-item" onclick="activer_controle_changer_support_seance('
            . $params . '); return false;" data-bs-toggle="modal" data-bs-target="#' . $this->id_dialogue_action . '">Changer de support</a>';
      }
      }
      $code = $code . $menu . '</div></div>';
      return $code;
    }
    
  }
  

  // ==========================================================================
?>
