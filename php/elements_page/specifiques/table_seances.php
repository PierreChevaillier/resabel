<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : Affichage des seances d'activite, par support et par creneau horaire
  // copyright (c) 2018-2024 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : bootstrap 4.x, resabel_ecran.css
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 15-jun-2019 pchevaillier@gmail.com
  // revision : 22-jan-2020 pchevaillier@gmail.com fermeture site et indispo supports
// revision : 31-may-2024 pchevaillier@gmail.com + affichage compet ou loisir (ou rien)
  // --------------------------------------------------------------------------
  // commentaires :
  // - en evolution
  // attention :
  //  - incomplet
  // a faire :
  // - affichage seance
  // - menus support, seance, creneau
  //   ...
  // ==========================================================================

  require_once 'php/metier/calendrier.php';
  require_once 'php/metier/support_activite.php';
  require_once 'php/metier/site_activite.php';
  
  require_once 'php/elements_page/specifiques/vue_seance_activite.php';
  // --------------------------------------------------------------------------
  class Table_Seances extends Element {
    
    private $activite_site;
    
    public $affiche_creation = false;
    
    protected $menu_action;
    public function def_menu_action($menu) {
      $this->menu_action = $menu;
    }
    
    public function __construct($page, $activite_site) {
      $this->def_page($page);
      $this->activite_site = $activite_site;
    }
    
    public function initialiser() {
      // Rien de specifique a faire ici ?
      // definir les creneaux a partir de la plage horaire
    }
    
    protected function afficher_debut(): void {
      echo "\n";
      echo '<div class="container-fluid" style="padding:0px;"><table class="table table-hover">';
      echo '<thead><tr><th></th>';
      foreach ($this->activite_site->creneaux_activite as $creneau) {
        $classe = '';
        $info = '';
        if ($this->activite_site->site_ferme_creneau($creneau->debut(), $creneau->fin())) {
          $classe = ' class="indispo"';
          $info = '<br />fermé';
        }
            
        echo '<th ', $classe, 'style="text-align:center;">', $creneau->debut()->heure_texte(), '<br />', $creneau->fin()->heure_texte(), $info, '</th>';
      }
      echo '</tr></thead><tbody>';
    }
    
    protected function afficher_seance(Seance_Activite $seance, int $index_creneau): void {
      $aff = new Afficheur_Vertical_Seance($this->page, $seance, $this->activite_site);
      // Menu des actions possibles sur la seance
      $ctrl = new Controleur_Action_Seance($aff, $index_creneau);
      echo $ctrl->formater_menu_action();

      // Affichage des informations sur la seance
      $code_html = $aff->formater();
      echo $code_html;
    }
    
    
    protected function afficher_corps(): void {
      // $this->page reference l'element modal qui permet d'afficher des informations sur les actions affectuees
      
      if (!isset($this->activite_site->site->supports_activite)) return; // on ne sait jamais...
      foreach ($this->activite_site->site->supports_activite as $code => $support) {
        echo '<tr>';
        if (is_a($support, 'Bateau')) {
          $sous_classe = '';
          if ($support->est_pour_competition()) $sous_classe = 'compet';
          elseif ($support->est_pour_loisir()) $sous_classe = 'loisir';
          echo '<td class="cel_bateau ' . $sous_classe . '">';
          echo '<div class="row h-100 justify-content-center align-items-center">';
          echo '<div class="num_bateau">'. $support->numero() . '</div><div class="nom_bateau">' .  $support->nom() . ' ' . $sous_classe . '</div>';
          echo '</div>';
          echo '</td>';
        } elseif (is_a($support, 'Plateau_Ergo'))  {
          echo '<td>' . $support->nom() . '</td>';
        }
        foreach ($this->activite_site->creneaux_activite as $i => $creneau) {
          $classe = '';
          $code_html = '';
          if ($this->activite_site->site_ferme_creneau($creneau->debut(), $creneau->fin()) || $this->activite_site->support_indisponible_creneau($support, $creneau->debut(), $creneau->fin()))
            $classe = 'indispo';
          
          $seance = $this->activite_site->seance_programmee($code, $i);
          if (is_null($seance)) {
            $seance = new Seance_Activite();
            $seance->support = $support;
            $seance->site = $this->activite_site->site;
            $seance->definir_horaire($creneau->debut(), $creneau->fin());
          }
          $id = $seance->support->code() . '_' . $seance->debut()->date_heure_sql();
          $classe = $classe . ' cel_seance';
          $classe = trim($classe); 
          echo '<td id="', $id, '" class="', $classe, '" style="padding:1px;text-align:center;">';
          
          $this->afficher_seance($seance, $i);
          echo '</td>';
        }
        echo '</tr>';
      }
    }
    
    protected function afficher_fin() {
      echo "</tbody></table></div>\n";
    }
  }
  
  // ==========================================================================
?>
