<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : Definition de la classe Page_Connexion
  // copyright (c) 2018-2023 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <nom_-fichier.php'
  // dependances :
  // teste avec : PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 17-jun-2018 pchevaillier@gmail.com
  // revision : 15-dec-2018 pchevaillier@gmail.com club parametrable
  // revision : 01-dec-2023 pchevaillier@gmail.com gestion form. connexion
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // -
  // a faire :
  // -
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/page.php';
  
  require_once 'php/elements_page/specifiques/entete_connexion.php';
  require_once 'php/elements_page/specifiques/formulaire_connexion.php';
  
  // --------------------------------------------------------------------------
  class Page_Connexion extends Page_Simple {
        
    public function definir_elements() {
      
      $script = "php/scripts/identification_perso.php?c=" . $_GET['c'] . "&s=" . $_GET['s'];
      $action = 'a'; // TODO: je ne sais pas a quoi ca sert ici
      $id = 'form_cnx_perso';
      $formulaire = new Formulaire_Connexion($this, $script, $action, $id);
      $this->ajoute_contenu($formulaire);
    }
    
    protected function afficher_debut(): void {
      parent::afficher_debut();
      $titre = Site_Web::accede()->sigle();
      $sous_titre = "Resabel";
      echo '<div class="mx-auto" style="width:80%">'; // pour que ce soit centre horizontalement
      
      echo '<div class="card" style="width:20rem;">'; // pour controler la largeur du formulaire
      echo '<div class="card-header">';
      echo '<div class="my-3 p-3 rounded bg-primary" style="text-align:center;color:white;">';
      echo '<h1>'. $titre . '</h1><p class="lead">' . $sous_titre . '</p>';
      echo '</div>';
      echo '</div>';
    }
    
    protected function afficher_fin(): void {
      echo '</div></div>';
      parent::afficher_fin();
    }
  }
  
  // ==========================================================================
?>
