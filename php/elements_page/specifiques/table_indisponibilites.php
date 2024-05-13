<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : Affichage des indisponibilites futures
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : bootstrap 4.x
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 10-jun-2019 pchevaillier@gmail.com
  // revision : 30-avr-2024 pchevaillier@gmail.com impacts modif classe Indisponibilite
  // --------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // ==========================================================================

  require_once 'php/metier/indisponibilite.php';
  require_once 'php/metier/calendrier.php';
  
  require_once 'php/metier/support_activite.php';
  //require_once 'php/bdd/enregistrement_support_activite.php';
  
  require_once 'php/metier/site_activite.php';
  //require_once 'php/bdd/enregistrement_site_activite.php';
  
  require_once 'php/metier/membre.php';
  require_once 'php/bdd/enregistrement_membre.php';
  
  require_once 'php/elements_page/specifiques/vue_personne.php';
  
  // --------------------------------------------------------------------------
  class Table_Indisponibilites extends Element {
    
    private $elements = array(); // elements de la table (1 ligne = 1 element)
    
    public $affiche_creation = true;
    
    protected $menu_action;
    public function def_menu_action($menu) {
      $this->menu_action = $menu;
    }
    
    public function def_elements($liste_elements) {
      $this->elements = $liste_elements;
    }
    
    public function __construct($page) {
      $this->def_page($page);
    }
    
    public function initialiser() {
      // Rien de specifique a faire ici
    }
    
    protected function afficher_debut() {
      echo '<div class="container"><table class="table table-sm table-striped table-hover">';
      echo '<tbody>';
    }
    
    protected function afficher_menu_actions(Indisponibilite $item): void {
      if (isset($this->menu_action)) {
        // il n'y a pas necessairement de menu (pourrait dependre du contexte)
        $this->menu_action->def_objet($item);
        $this->menu_action->afficher();
      }
    }
    
    protected function afficher_corps() {
      
      //$presentation_nom = new Afficheur_Nom();
      
      if (!isset($this->elements)) return; // on ne sait jamais...
      foreach ($this->elements as $item) {
        echo '<tr>';
        if (is_a($item, 'Indisponibilite_Support')) {
          /*
          $enreg = new Enregistrement_Support_Activite();
          $enreg->def_support_activite($item->support);
          $enreg->lire_identite();
          echo '<td>'. $enreg->support_activite()->numero() . '</td>';
          echo '<td>'. $enreg->support_activite()->nom() . '</td>';
          echo '<td>'. $enreg->support_activite()->nom_type() . '</td>';
           */
         
          echo '<td>'. $item->support->identite_texte() . '</td>';
        } elseif (is_a($item, 'Fermeture_Site'))  {
          /*
          $enreg_site = new Enregistrement_Site_Activite();
          $enreg_site->def_site_activite($item->site_activite);
          $enreg_site->lire_identite();
          echo '<td>' . $enreg_site->site_activite()->nom() . '</td>';
           */
          echo '<td>' . $item->site_activite->nom() . '</td>';
        }
   
        echo '<td>' . $item->motif()->nom() . '</td>';
        echo '<td>' . $item->formatter_periode() . '</td>';
        echo '<td>' . $item->information() . '</td>';
       
        if ($this->affiche_creation) {
          $creation = "saisie le " . $item->instant_creation()->date_texte_court();
          echo '<td>' . $creation . '</td>';
        
          if (!is_null($item->createurice())) {
            $enreg_prs = new Enregistrement_Membre();
            $enreg_prs->def_membre($item->createurice());
            $enreg_prs->lire();
          
//            $presentation_nom->def_personne($item->createur);
 //           $createur = ' par ' . $presentation_nom->formatter();
          }
          $nom_createurice = $item->identite_createurice();
          echo '<td>par ' . $nom_createurice . '</td>';
        }
        echo '<td>';
        $this->afficher_menu_actions($item);
        echo '</td></tr>';
      }
    }
    
    protected function afficher_fin() {
      echo "</tbody></table></div>\n";
    }
  }
  
  // ==========================================================================
?>
