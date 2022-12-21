<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classes definissant les 'vues' d'un objet de la classe
  //               Seance_activite
  //               generation du code html pour affichage des informations
  //               sur une seance d'activite
  // copyright (c) 2018-2020 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : bootstratp 4.x
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 22-jan-2020 pchevaillier@gmail.com
  // revision : 25-aug-2020 pchevaillier@gmail.com contexte actions + des actions
  // --------------------------------------------------------------------------
  // commentaires :
  // - en evolution
  // - s'inspirer de resabel V1 (sortie_presentations.php)
  //   donc pas 1 Element ?
  // attention :
  // - experimental
  // a faire : Afficheur simple : sans controle et Afficheur avec controle(s)
  //  - afficher information : sortie et participant
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
    private $session_admin = false;
    public $session_pers = false;
    private $session_club = false;
    private $membre_actif = false;
    private $responsable = false;
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
      $this->session_interactive = ($this->session_club || $this->session_admin || ($this->action == 'ie'));
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
    
    static function creer(Page $page, Seance $objet_metier) {
      $vue = null;
      /*
      if (is_a($objet_metier, 'Regime_Diurne'))
        $vue = new Afficheur_Regime_Diurne($page);
      elseif (is_a($objet_metier, 'Regime_Hebdomadaire'))
        $vue = new Afficheur_Regime_Hebdomadaire($page);
      $vue->def_regime($objet_metier);
       */
      return $vue;
    }
    
    protected $seance;
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
    protected $couleur_texte_debutant = "white";
    
    protected $couleur_fond_place_libre = " #DAF7A6"; //LightCyan";
    protected $couleur_texte_place_libre = "Black";
    
    protected $pas_encore_controle_vide = true;
    
    public function __construct($page, $seance, $activite_site) {
      $this->def_page($page);
      $this->seance = $seance;
      $this->activite_site = $activite_site;
      $this->id_dialogue_action = "aff_act_" . $this->seance->site->code(); //$this->activite_site->site->code();
      $this->params_action_seance = '\'' . $this->id_dialogue_action . '\', '
      . $this->seance->code() . ', '
      . $this->seance->site->code() . ', '
      . $this->seance->code_support() . ', '
      . '\'' . $this->seance->debut()->date_heure_sql() . '\', '
      . '\'' . $this->seance->fin()->date_heure_sql() . '\'';
    }
    
    public function initialiser() { }
    
    public function generer_id(int $rang) {
      return $this->seance->support->code() . '_' . $this->seance->debut()->date_heure_sql() . '_' . $rang;
    }
    
    protected function afficher_debut() {
      echo "\n<div style=\"padding:4px\">\n";
    }
    
    protected function afficher_fin() {
      echo "</div>\n";
    }
    
    abstract function formater();
    
    protected function formater_participant(Participation_Activite $p,
                                            int $rang,
                                            String & $code_html) {
      $personne = $p->participant;
      if (($this->seance->a_un_responsable()) && ($personne->code() == $this->seance->responsable->code()))
        return false;
      
      $couleur_texte = ($personne->est_debutant()) ?  $this->couleur_texte_debutant :  $this->couleur_texte_equipier;
      $couleur_texte = ($personne->est_chef_de_bord()) ?  $this->couleur_texte_cdb :  $couleur_texte;
      $couleur_fond = ($personne->est_debutant()) ? $this->couleur_fond_debutant :  $this->couleur_fond_equipier;

      $str_participant = utf8_encode(''); //$personne->prenom . " " . $personne->nom;
      $code_interacteur = utf8_encode('');
      
      if ($this->est_interactif) {
        $code_interacteur = $code_interacteur . $this->generer_code_interacteur($personne);
/*
        $desinscription_possible = ($personne->code() == $this->contexte_action()->utilisateur->code());
        if ($desinscription_possible) {
          $resp = 0;
          $params = $this->params_action_seance . ', ' . $personne->code() . ', ' . $resp;
          $code_action = "di";
          $params = $params . ', \'' . $code_action . '\'';
          $code_interacteur = '&nbsp;<span><img src="../../assets/icons/x-square.svg" alt="X" width="24" height="24" class="rsbl-tooltip" data-toggle="modal" data-target="#' . $this->id_dialogue_action . '" title="Annulation inscription" onclick="requete_inscription_individuelle(' . $params . ');"></span>';
        }
      */
      } else {
         $str_participant = $personne->prenom . " " . $personne->nom;
      }
      $code_html = $code_html . "<td id=\"" . $this->generer_id($rang) . "\" style =\"width:" . $this->largeur . "px;color:" . $couleur_texte . ";background-color:" . $couleur_fond . ";text-align:center;padding:1px\"><div style=\"min-height:31px\">" . $str_participant . ' ' . $code_interacteur . "</div></td>";
      return true;
    }
    
    protected function generer_code_interacteur(Membre $participant) {
      $code = utf8_encode('');
      $code = $code . '<div class="dropdown">';
       
      // --- le menu pour interagir : effectuer une action sur la participation a l'activite
      $texte_bouton = utf8_encode('');
      $texte_bouton = substr($participant->prenom . ' ' . $participant->nom, 0, 22);
      $id_menu = 'mnu_particip_' . $this->seance->support->code() . '_' . $this->seance->debut()->date_heure_sql() . '_' . $participant->code();
      $code = $code . '<button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" style="min-width:196px;" id="' . $id_menu . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . $texte_bouton . '</button>';
       
      $code = $code . '<div class="dropdown-menu" aria-labelledby="' . $id_menu . '">';
      
      // --- Actions du menu toujours possibles
      $menu = utf8_encode('');
      $params = $participant->code() . ', \'' . $this->id_dialogue_action . '\'';
      $menu = $menu . '<a class="dropdown-item" data-toggle="modal" data-target="#' . $this->id_dialogue_action . '" onclick="requete_info_personne(' . $params . '); return false;">Afficher infos</a>';
      
      // --- Actions dependant du contexte
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
        $menu = $menu . '<a class="dropdown-item" data-toggle="modal" data-target="#' . $this->id_dialogue_action . '" onclick="requete_inscription_individuelle(' . $params . ');return false;">Annuler participation</a>';
      }
      
      // Passage du role responsable au role equipier. Possible si
      // - action autorisee
      // - ET le participant est le responsable
      // - ET il y a une place libre
      $place_libre_equipier = $this->seance->nombre_places_disponibles() > ($this->seance->a_un_responsable() ? 0:1);
      $passage_possible = $action_autorisee && $resp && $place_libre_equipier;
      if ($passage_possible) {
        $params = $this->seance->code . ', ' . $participant->code();
        $code_action = "mre"; // Modification : passage Responsable a Equipier
        $params = $params . ', \'' . $code_action . '\'';
        $menu = $menu . '<a class="dropdown-item" onclick="requete_changement_role_seance(' . $params . ');return false;">Passer équipier</a>';
      }

      // Passage du role equipier au role responsable. Possible si :
      // - action autorisee
      // - le participant est un equipier
      // - le type d'activite requiert un responsable participant
      // - il n'y a pas de responsable
      // - le participant est qualifie (chef de bord)
      $possible = $this->seance->responsable_requis() && !$this->seance->a_un_responsable() && $participant->est_chef_de_bord() && !$this->seance->a_comme_responsable($participant);
      $passage_possible = $action_autorisee && $possible;
      if ($passage_possible) {
        $params = $this->seance->code . ', ' . $participant->code();
        $code_action = "mer"; // Modification : passage Equipier a Responsable
        $params = $params . ', \'' . $code_action . '\'';
        $menu = $menu . '<a class="dropdown-item" onclick="requete_changement_role_seance(' . $params . ');return false;">Passer Chef de bord</a>';

      }
      $code = $code . $menu . '</div></div>';
      
      /*
      $menu = '<div class="list-group">';
      $menu = $menu . '<a href="#" class="list-group-item list-group-item-action active">Afficher infos</a><a href="#" class="list-group-item list-group-item-action">Passer équipier</a>';
      
      $params = $this->params_action_seance . ', ' . $personne->code() . ', ' . $resp;
               $code_action = "di";
               $params = $params . ', \'' . $code_action . '\'';
      
      $menu = $menu . '<a href="https://www.enib.fr" class="list-group-item list-group-item-action">ZZZZ Annulation inscription</a>';
      $menu = $menu . '<a href="#" class="list-group-item list-group-item-action">Changer horaire</a><a href="#" class="list-group-item list-group-item-action">Changer support</a>';
      $menu = $menu . '</div>';
      //$menu = addslashes($menu);
      $menu = htmlspecialchars($menu);
      $code = $code . '<button class="btn btn-sm btn-outline-primary">';
      $code = $code . '<a tabindex="0" data-html="true" data-container="body" data-trigger="focus" data-toggle="popover" data-placement="top" title="<strong>Actions</strong>" data-content="' . $menu . '"><img src="../../assets/icons/list.svg" alt="!" width="20" height="20"></a>';
      $code = $code . '</button>';
      */
      
      return $code;
    }
    
    protected function formater_responsable(String & $code_html) {
      $str_resp = utf8_encode('');
      $code_interacteur = utf8_encode('');
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
            $code_interacteur = '<img src="../../assets/icons/pencil-square.svg" alt="+" width="24" height="24" class="rsbl-tooltip" data-toggle="modal" data-target="#' . $this->id_dialogue_action . '" title="Inscription chef de bord" onclick="requete_inscription_individuelle(' . $params . ');">';
          }
        }
      }
      $code_html = $code_html . "<td id=\"" . $this->generer_id(0) . "\" style =\"width:" . $this->largeur . "px;color:" . $this->couleur_texte_resp . ";background-color:" . $this->couleur_fond_resp . ";text-align:center;padding:1px\"><div style=\"min-height:31px\">" . $str_resp . $code_interacteur . "</div></td>";
      
      //$code_html = $code_html . "<td width=\"" . $this->largeur . "px\" bgcolor=\"" . $this->couleur_fond_resp . "\" align=\"center\">" . $str_resp . "</td>";
      return;
    }

    protected function formater_place_libre(int $rang, String & $code_html) {
      $code_html = $code_html . "<td id=\"" . $this->generer_id($rang) . "\" style =\"width:" . $this->largeur . "px;min-height:300px;color:" . $this->couleur_texte_place_libre . ";background-color:" . $this->couleur_fond_place_libre . ";text-align:center;padding:1px\"><div style=\"min-height:31px\">";
      $code_html = $code_html . "&nbsp;";
      $code_interacteur = utf8_encode('');
      if ($this->est_interactif) {
        $inscription_possible = $this->contexte_action()->inscription_individuelle()
          && !$this->activite_site->participe_activite_creneau($this->contexte_action()->utilisateur, $this->seance->plage_horaire);
        $creneau = $this->seance->plage_horaire;
        $indispo = ($this->activite_site->site_ferme_creneau($creneau->debut(), $creneau->fin()) || $this->activite_site->support_indisponible_creneau($this->seance->support, $creneau->debut(), $creneau->fin()));
        $inscription_possible =  $inscription_possible && !$indispo;
        
        $code_interacteur = utf8_encode('');
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
          $code_interacteur = '<img src="../../assets/icons/pencil-square.svg" alt="inscrire" width="24" height="24" class="rsbl-tooltip" data-toggle="modal" data-target="#' . $this->id_dialogue_action . '" title="Inscription équipier" onclick="requete_inscription_individuelle(' . $params . ');">';
          
          $this->pas_encore_controle_vide = false;
        }
      }
      $code_html = $code_html . $code_interacteur . "</div></td>";
      return;
    }
    
  }
  
  // --------------------------------------------------------------------------
   class Afficheur_Vertical_Seance extends Afficheur_Seance_Activite {
     
     public function formater() {
       //$this->afficher_debut();
       $code_html = utf8_encode('');
       $code_html = $code_html . "\n<div style=\"padding:2px;\">\n";
       $code_html = $code_html . "\n\t<table class=\"table table-bordered table-condensed\" style=\"width:"
                    . ($this->largeur + 0) . "px;\"><tbody>\n";
    
       // le responsable TODO : tester si activite avec responsable participant
       if ($this->seance->responsable_requis()) {
         $code_html = $code_html . "\t\t<tr>";
         $this->formater_responsable($code_html);
         $code_html = $code_html . "\t\t</tr>\n";
       }
       
       // les equipiers
       $rang = 1;
       $ajout = false;
       foreach ($this->seance->inscriptions as $participation) {
         $code_html = $code_html . "<tr>";
         $ajout = $this->formater_participant($participation, $rang, $code_html);
         $code_html = $code_html . "</tr>\n";
         if ($ajout) $rang++;
       }
    
       // les places vides
       $n_vides = $this->seance->nombre_places_disponibles();
       if ($this->seance->responsable_requis() && !$this->seance->a_un_responsable())
         $n_vides -= 1;
       
       for ($v = 0; $v < $n_vides; $v++) {
         $code_html = $code_html .  "<tr>";
         $this->formater_place_libre($rang, $code_html);
         $code_html = $code_html . "</tr>\n";
         $rang++;
       }
    
       $code_html = $code_html . "\t</tbody></table>\n";
       $code_html = $code_html . "</div>\n";
       return $code_html;
       
     }
     
     protected function afficher_corps() {
       $code_html = utf8_encode('');
       echo $code_html;
       return;
     }
        
   }

  // --------------------------------------------------------------------------
  class Controleur_Action_Seance {
    public $seance = null; // objet metier
    public $page = null; // pour ajouter des javascripts
    public $activite_site = null; // contexte : activite du site sur la periode
    protected $mode_interactif = false;
    protected $afficheur = null;
    protected $id_dialogue_action = "";
    
    public function __construct(Afficheur_Seance_Activite $afficheur) {
      $this->page = $afficheur->page();
      $this->seance = $afficheur->seance();
      $this->activite_site = $afficheur->activite_site;
      $this->mode_interactif = $afficheur->est_interactif;
      $this->afficheur = $afficheur;
      $this->id_dialogue_action = "aff_act_" . $this->activite_site->site->code();
    }
    
    protected function formater_info_seance() {
      $code = utf8_encode('');
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
      $code = utf8_encode('');
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
      $code = utf8_encode('');
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
    
    public function formater_menu_action() {
      $code = utf8_encode('');

      $code = $code . '<div class="dropdown">';
      
      // --- le bouton pour interagir
      $texte_bouton = utf8_encode('');
      if (is_a($this->seance->support, 'Bateau'))
        $code_support = $this->seance->support->numero();
      else
        $code_support = $this->seance->support->nom();
      $texte_bouton = $texte_bouton . $code_support . ' à ' . $this->seance->debut()->heure_texte();
      $id_menu = 'mnu_seance_' . $this->seance->support->code() . '_' . $this->seance->debut()->date_heure_sql();
      $code = $code . '<button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="' . $id_menu . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . $texte_bouton . '</button>';
      
      $code = $code . '<div class="dropdown-menu" aria-labelledby="' . $id_menu . '">';
     
      // --- Actions du menu toujours possibles
      $menu = utf8_encode('');
      
      // copie de page_accueil_perso.php
      $details = utf8_encode("");
      $seance = $this->seance;
      $aff_tel = new Afficheur_Telephone();
      $aff_mail = new Afficheur_Courriel_Actif();
      
      $entete = $seance->site->nom() . ", le " . $seance->debut()->date_texte_court()
      . " de " . $seance->debut()->heure_texte() . " à " . $seance->fin()->heure_texte();
      $sujet = "Sortie du " . $seance->debut()->date_texte()
             . " à " . $seance->debut()->heure_texte();
      $info_support = " sur " . $seance->support->nom();
      if (is_a($seance->support, "Bateau"))
        $info_support = $info_support . " (" . $seance->support->numero() . ")";
      $sujet = $sujet . " " . $info_support;
      $sujet = $sujet . " - " . $seance->site->nom();
      $entete = $entete . $info_support;
      foreach ($seance->inscriptions as $participation) {
        $p = $participation->participant;
        $details = $details . $p->prenom() . " " . $p->nom();
        $details = $details . " " . $aff_tel->formatter($p->telephone);
        $aff_mail->def_personne($p);
        $details = $details . " " . $aff_mail->formatter("Je te contacte ", $sujet);
        $details = $details . "<br />";
      }
      $details = htmlspecialchars($details); // indispensable car il ya des " dans $details
      $modal_id = "aff_act";
      $menu = $menu . '<a class="dropdown-item" data-toggle="modal" data-target="#' . $this->id_dialogue_action . '" onclick="return afficher_info_seance(\'' . $this->id_dialogue_action . '\', \''
          . $entete . '\', \'' . $details . '\');">Afficher informations</a>';
      
      //$menu = $menu . '<a href="#" class="dropdown-item">Afficher infos</a>';
      
      // --- Actions dependant du contexte
      //if ($this->mode_interactif) {
      $contexte = $this->afficheur->contexte_action();
      $action_autorisee = $contexte->session_interactive;
      $action_possible = true;
      if (!$action_autorisee && $contexte->session_pers) {
        $action_possible = $this->seance->a_comme_participant($contexte->utilisateur);
      }
      
      if ($action_possible) {
        // parametres communs a tous les scripts agissant sur la seance
        $params_seance = $this->seance->code() . ', '
                        . $this->activite_site->site->code() . ', '
                        . $this->seance->code_support() . ', '
                        . '\'' . $this->seance->debut()->date_heure_sql() . '\', '
                        . '\'' . $this->seance->fin()->date_heure_sql() . '\'';
        
        if ($this->seance->responsable_requis() && ! $this->seance->a_un_responsable()) {
          // inscription d'une personne en tant que chef de bord de la seance
          $rang = 0;
          $id_participation = $this->afficheur->generer_id($rang);
          $params = $params_seance . ', \'' . $id_participation . '\', ' . $rang;
          $menu = $menu . '<a class="dropdown-item" onclick="activer_formulaire(' . $params . '); return false; " >Inscrire Chef de bord</a>';
        }
        $place_equipier = $this->seance->nombre_places_disponibles() > ($this->seance->a_un_responsable() ? 0:1);
        if ($place_equipier) {
          $rang = $this->seance->support->capacite() - $this->seance->nombre_places_disponibles() + ($this->seance->a_un_responsable() ? 0:1);
          $id_participation = $this->afficheur->generer_id($rang);
          $params = $params_seance . ', \'' . $id_participation . '\', ' . $rang;
          $menu = $menu . '<a class="dropdown-item" onclick="activer_formulaire(' . $params . '); return false;" >Inscrire équipier</a>';
        }
        
        if ($this->seance->nombre_participants() > 0) {
          $html_info_seance = htmlspecialchars($this->formater_info_seance());
          $html_info_participations = htmlspecialchars($this->formater_info_participations());
          $params = $this->seance->code() . ', \'' . $this->id_dialogue_action . '\', \'' . $html_info_seance . '\', \'' . $html_info_participations . '\', \'' . htmlspecialchars($this->formater_mail_participants()) . '\'';
          $menu = $menu . '<a class="dropdown-item" onclick="activer_controle_annulation_seance(' . $params . '); return false;" data-toggle="modal" data-target="#' . $this->id_dialogue_action . '">Annuler séance</a>';
          $menu = $menu . '<a class="dropdown-item" onclick="return false;">Changer horaire</a>';
          $menu = $menu . '<a class="dropdown-item" onclick="return false;">Changer support</a>';
        }
      }
      
      $code = $code . $menu . '</div></div>';
      return $code;
    }
    
  }
  
  
  /*
  class Controle_Participation_Activite {
    private $parent = null;
    
    public function __construct($parent) {
      $this->parent = $parent;
    }
    
    public function formater() {
      $code_html = utf8_encode('');
      $modal_id = "aff_Act_" . $this->parent->activite_site->site->code();
      $params = $modal_id . ', '
        . $this->parent->activite_site->site->code() . ', '
        . $this->parent->seance->code_support() . ', '
        . $this->parent->seance->debut() . ', '
        . $this->parent->seance->fin();
      
      $parmas = $params . ', ' . $this->parent->contexte_action()->utilisateur; //, responsable';
      
      $code_html = $code_html . '<img src="../../assets/icons/pencil.svg" alt="" width="24" height="24" data-toggle="modal" data-target="#aff_act_1" title="inscription" onclick=requete_inscription_individuelle(' . $params . ');>';
      return $code_html;
    }
  }
  */
  // ==========================================================================
?>
