<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : Affichage des seances d'activite, par support et par creneau horaire
  // copyright (c) 2018-2020 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : bootstrap 4.x, resabel_ecran.css
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 15-jun-2019 pchevaillier@gmail.com
  // revision : 22-jan-2020 pchevaillier@gmail.com fermeture site et indispo supports
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
    
    protected function afficher_debut() {
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
    
    protected function afficher_menu_actions($item) {
      echo '<div class="dropdown"><button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>';
      echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
      echo '<a class="dropdown-item" data-toggle="modal" data-target="#aff_mbr" href="#" onclick="return true;">Afficher</a>';
       echo '</div></div>', PHP_EOL;
      
      /*
      if (isset($this->menu_action)) {
        // il n'y a pas necessairement de menu (depend du contexte)
        $this->menu_action->seance = $item;
        $this->menu_action->initialiser();
        $this->menu_action->afficher();
      }
       */
      
    }
    
    protected function afficher_corps() {
      // $this->page reference l'element modal qui permet d'afficher des informations sur les actions affectuees
      
      if (!isset($this->activite_site->site->supports_activite)) return; // on ne sait jamais...
      foreach ($this->activite_site->site->supports_activite as $code => $support) {
        echo '<tr>';
        if (is_a($support, 'Bateau')) {
          $sous_classe = '';
          if ($support->est_pour_competition()) $sous_classe = 'compet';
          elseif ($support->est_pour_loisir()) $sous_classe = 'loisir';
          echo '<td class="cel_bateau ' . $sous_classe . '"><div class="num_bateau">'. $support->numero() . '</div><div class="nom_bateau">' .  $support->nom() . '</div></td>';
        } elseif (is_a($support, 'Plateau_Ergo'))  {
          echo '<td>' . $support->nom() . '</td>';
        }
        foreach ($this->activite_site->creneaux_activite as $i => $creneau) {
          $classe = '';
          $code_html = '';
          if ($this->activite_site->site_ferme_creneau($creneau->debut(), $creneau->fin()) || $this->activite_site->support_indisponible_creneau($support, $creneau->debut(), $creneau->fin()))
            $classe = ' class="indispo"';
          
          //$aff = new Afficheur_Vertical_Seance($this->page, );
          $seance = $this->activite_site->seance_programmee($code, $i);
          /*
          if (!is_null($seance)) {
            $aff->def_seance($seance);
          } else {
            $s = new Seance_Activite();
            $s->support = $support;
            $s->definir_horaire($creneau->debut(), $creneau->fin());
            $aff->def_seance($s);
          }
           */
          if (is_null($seance)) {
            $seance = new Seance_Activite();
            $seance->support = $support;
            $seance->definir_horaire($creneau->debut(), $creneau->fin());
          }
          $aff = new Afficheur_Vertical_Seance($this->page, $seance, $this->activite_site);
          
          $code_html = $aff->formater();

          echo '<td', $classe, ' style="padding:1px; text-align:center;">', $code_html;
          $this->afficher_menu_actions($seance);
          echo '</td>';
        }
//        echo '<td>';
//        $this->afficher_menu_actions($item);
        echo '</tr>';
      }
    }
    
    protected function afficher_fin() {
      echo "</tbody></table></div>\n";
    }
  }
  
  // ==========================================================================
?>
