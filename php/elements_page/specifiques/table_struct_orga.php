<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : affichage de la liste des roles des personnes dans une composante
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : bootstrap 4.x
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 25-mai-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // -
  // a faire :
  // -
  // ==========================================================================

  require_once 'php/metier/struct_orga.php';
  require_once 'php/elements_page/specifiques/vue_personne.php';
  require_once 'php/bdd/enregistrement_struct_orga.php';
  require_once 'php/utilitaires/format_donnees.php';
  
  // --------------------------------------------------------------------------
  class Table_Entite_Organisationnelle extends Element {
    
    private $roles_membres = array();
    private $composante = null;
    
    public function __construct($page, $composante, $roles_membres) {
      $this->def_page($page);
      $this->composante = $composante;
      
      // on ne retient que les informations sur la composante
      foreach ($roles_membres as $rm) {
        if ($rm->composante()->code() == $this->composante->code())
          $this->roles_membres[] = $rm;
      }
    }
    
    public function initialiser() {
      // rien a faire
    }
    
    protected function afficher_debut() {
      echo '<div class="container"><table class="table table-sm table-striped table-hover">';
      echo '<tbody>';
      //if (strlen($this->legende) > 0)
      //  echo '<caption>' . $this->legende . '</caption>';
    }
    
    
    protected function afficher_corps() {
      $presentation_nom = new Afficheur_Nom();
      $presentation_tel = new Afficheur_telephone();
      $presentation_courriel = new Afficheur_Courriel_Actif();
      $sujet_courriel = ""; // pas de sujet particulier ici
      
      foreach ($this->roles_membres as $rm) {
        echo '<tr>';
        $p = $rm->personne();
        
        $nom_role = $p->est_femme() ? $rm->role()->nom_feminin : $rm->role()->nom_masculin;
        echo '<td>' . $nom_role . '</td>';
        
        $presentation_nom->def_personne($p);
        $presentation_courriel->def_personne($p);
        echo '<td>' . $presentation_nom->formatter() . '</td>';
        echo '<td><span>' . $presentation_tel->formatter($p->telephone) . '</span></td>';
        echo '<td>' . $presentation_courriel->formatter("Je te contacte pour ",  $sujet_courriel) . '</td><td>' . $p->nom_commune . '</td>';
        
        //echo '<td>';
        //$this->afficher_menu_actions($p);
        //echo '</td>';
        echo '</tr>';
      }
    }
    
    protected function afficher_fin() {
      echo "</tbody></table></div>\n";
    }
  }
  
  // ==========================================================================
?>
