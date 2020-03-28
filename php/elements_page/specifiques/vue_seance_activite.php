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
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // - en evolution
  // - s'inspirer de resabel V1 (sortie_presentations.php)
  //   donc pas 1 Element ?
  // attention :
  // - experimental
  // a faire : Afficheur simple : sans controle et Afficheur avec controle(s)
  // ==========================================================================
  
  // --------------------------------------------------------------------------
  require_once 'php/metier/seance_activite.php';
  require_once 'php/metier/personne.php';
  
  require_once 'php/elements_page/generiques/element.php';
  
  // --------------------------------------------------------------------------
  class Contexte_Action_Seance {
    public $page = null;
    private $session_admin = false;
    private $session_pers = false;
    private $session_club = false;
    private $membre_actif = false;
    private $responsable = false;
    public $utilisateur = null;
    private $action = '';
    public function code_action() { return $this->action; }
    private $parametres = array();
    
    public function __construct($page) {
      $this->page = $page;
    }
    
    public function initialiser() {
      $this->session_admin = isset($_SESSION['adm']) && $_SESSION['adm'];
      $this->session_pers = isset($_SESSION['prs']) && $_SESSION['prs'];
      $this->session_club = ! $this->session_pers;
      $this->membre_actif = $this->session_pers && isset($_SESSION['usr']) && isset($_SESSION['act']);
      $this->responsable = $this->session_pers && isset($_SESSION['usr']) && isset($_SESSION['cdb']);
      if ($this->session_pers) {
        $this->utilisateur = new Membre($_SESSION['usr']);
      }
      if (isset($_GET['a'])) $this->action = $_GET['a'];

      if ($this->inscription_individuelle())
        $this->page->javascripts[] = "js/requete_inscription_individuelle.js";
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
    
    protected function contexte_action() { return $this->page()->contexte_action(); }
    
    protected $largeur = 200;
    protected $couleur_fond_resp = "#FFE066";
    protected $couleur_texte_resp = "Black";
    
    protected $couleur_texte_cdb = "Red";
    
    protected $couleur_fond_equipier = "Cornsilk";
    protected $couleur_texte_equipier = "Black";
    
    protected $couleur_fond_debutant = "#136BAC";
    protected $couleur_texte_debutant = "white";
    
    protected $couleur_fond_place_libre = "LightCyan";
    protected $couleur_texte_place_libre = "Black";
    
    protected $pas_encore_controle_vide = true;
    
    public function __construct($page, $seance, $activite_site) {
      $this->def_page($page);
      $this->seance = $seance;
      $this->activite_site = $activite_site;
    }
    
    public function initialiser() { }
    
    protected function afficher_debut() {
      echo "\n<div style=\"padding:4px\">\n";
    }
    
    protected function afficher_fin() {
      echo "</div>\n";
    }
    
    abstract function formater();
    
    protected function formater_bouton_action() {
      $code_html = '<div class="dropdown"><button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>';
      $code_html = $code_html . '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
      $code_html = $code_html . '<a class="dropdown-item" data-toggle="modal" data-target="#aff_mbr" href="#" onclick="return true;">Afficher</a>';
      $code_html = $code_html . '</div></div>';
      return $code_html;
    }
    
    protected function formater_participant(Participation_Activite $p,
                                            String & $code_html) {
      $personne = $p->participant;
      if (($this->seance->a_un_responsable()) && ($personne->code() == $this->seance->responsable->code())) return;
      
      $couleur_texte = ($personne->est_debutant()) ?  $this->couleur_texte_debutant :  $this->couleur_texte_equipier;
      $couleur_texte = ($personne->est_chef_de_bord()) ?  $this->couleur_texte_cdb :  $couleur_texte;
      
      $couleur_fond = ($personne->est_debutant()) ? $this->couleur_fond_debutant :  $this->couleur_fond_equipier;
      $str_participant = $personne->prenom . " " . $personne->nom;
      $str_bouton = '';
      //$this->formater_bouton_action() ;
      
      $code_html = $code_html . "<td style =\"width:" . $this->largeur . "px;color:" . $couleur_texte . ";background-color:" . $couleur_fond . ";text-align:center;padding:1px\">" . $str_participant . ' ' . $str_bouton . "</td>";
      return;
    }
    
    protected function formater_responsable(String & $code_html) {
      $str_resp = utf8_encode('&nbsp;');
      $code_interacteur = utf8_encode('');
      if ($this->seance->a_un_responsable()) {
        $str_resp = $this->seance->responsable->prenom . " " . $this->seance->responsable->nom;
      } else {
        if ($this->est_interactif) {
          $inscription_possible = $this->contexte_action()->inscription_individuelle()
            && !$this->activite_site->participe_activite_creneau($this->contexte_action()->utilisateur, $this->seance->plage_horaire)
            && $this->contexte_action()->utilisateur_responsable();
          if ($inscription_possible) {
            $modal_id = "aff_act_" . $this->activite_site->site->code();
            $params = '\'' . $modal_id . '\', '
                      . '\'' . $this->contexte_action()->code_action() . '\', '
                      . $this->seance->code() . ', '
                      . $this->activite_site->site->code() . ', '
                      . $this->seance->code_support() . ', '
                      . '\'' . $this->seance->debut()->date_heure_sql() . '\', '
                      . '\'' . $this->seance->fin()->date_heure_sql() . '\'';
            $params = $params . ', ' . $this->contexte_action()->utilisateur->code() . ', 1'; // 1: participation en tant que responsable (chef de bord)
            $code_interacteur = '<img src="../../assets/icons/pencil.svg" alt="" width="24" height="24" data-toggle="modal" data-target="#' . $modal_id . '" title="inscription" onclick="requete_inscription_individuelle(' . $params . ');">';
          }
        }
      }
      $code_html = $code_html . "<td style =\"width:" . $this->largeur . "px;color:" . $this->couleur_texte_resp . ";background-color:" . $this->couleur_fond_resp . ";text-align:center;padding:1px\">" . $str_resp . $code_interacteur . "</td>";
      
      //$code_html = $code_html . "<td width=\"" . $this->largeur . "px\" bgcolor=\"" . $this->couleur_fond_resp . "\" align=\"center\">" . $str_resp . "</td>";
      return;
    }

    protected function formater_place_libre(String & $code_html) {
      $code_html = $code_html . "<td style =\"width:" . $this->largeur . "px;color:" . $this->couleur_texte_place_libre . ";background-color:" . $this->couleur_fond_place_libre . ";text-align:center;padding:1px\">";
      $code_html = $code_html . "&nbsp;";
      $code_interacteur = utf8_encode('');
      if ($this->est_interactif) {
        $inscription_possible = $this->contexte_action()->inscription_individuelle()
          && !$this->activite_site->participe_activite_creneau($this->contexte_action()->utilisateur, $this->seance->plage_horaire);
        $code_interacteur = utf8_encode('');
        if ($inscription_possible && $this->pas_encore_controle_vide) {
          $modal_id = "aff_act_" . $this->activite_site->site->code();
          $params = '\'' . $modal_id . '\', '
                    . '\'' . $this->contexte_action()->code_action() . '\', '
                    . $this->seance->code() . ', '
                    . $this->activite_site->site->code() . ', '
                    . $this->seance->code_support() . ', '
                    . '\'' . $this->seance->debut()->date_heure_sql() . '\', '
                    . '\'' . $this->seance->fin()->date_heure_sql() . '\'';
          $params = $params . ', ' . $this->contexte_action()->utilisateur->code() . ', 0'; // 0 : participation pas en tant que responsable (chef de bord)
          $code_interacteur = '<img src="../../assets/icons/pencil.svg" alt="" width="24" height="24" data-toggle="modal" data-target="#' . $modal_id . '" title="inscription" onclick="requete_inscription_individuelle(' . $params . ');">';
          
          $this->pas_encore_controle_vide = false;
        }
      }
      $code_html = $code_html . $code_interacteur . "</td>";
      return;
    }
    
  }
  
  // --------------------------------------------------------------------------
   class Afficheur_Vertical_Seance extends Afficheur_Seance_Activite {
     
     public function formater() {
       //$this->afficher_debut();
       $code_html = utf8_encode('');
       $code_html = $code_html . "\n<div style=\"padding:2px\">\n";
       $code_html = $code_html . "\n\t<table class=\"table table-bordered table-condensed\" style=\"width:"
                    . ($this->largeur + 0) . "px;\"><tbody>\n";
    
       // le responsable TODO : tester si activite avec responsable participant
       if ($this->seance->responsable_requis()) {
         $code_html = $code_html . "\t\t<tr>";
         $this->formater_responsable($code_html);
         $code_html = $code_html . "\t\t</tr>\n";
       }
       
       // les equipiers
       foreach ($this->seance->inscriptions as $participation) {
         $code_html = $code_html . "<tr>";
         $this->formater_participant($participation, $code_html);
         $code_html = $code_html . "</tr>\n";
       }
    
       // les places vides
       $n_vides = $this->seance->nombre_places_disponibles();
       if ($this->seance->responsable_requis() && !$this->seance->a_un_responsable())
         $n_vides -= 1;
       
       for ($v = 0; $v < $n_vides; $v++) {
         $code_html = $code_html .  "<tr>";
         $this->formater_place_libre($code_html);
         $code_html = $code_html . "</tr>\n";
       }
    
       $code_html = $code_html . "\t</tbody></table>\n";
       $code_html = $code_html . "</div>\n";
       //$code_html = $code_html . "</div>\n";
       return $code_html;
       
     }
     
     protected function afficher_corps() {
       $code_html = utf8_encode('');
       echo $code_html;
       return;
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
