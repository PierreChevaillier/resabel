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
    
    /*public function __construct($nom_site, $nom_page, $feuilles_style = null) {
      parent::__construct($nom_site, $nom_page, $feuilles_style);
      //$this->javascripts[] = "js/controle_identification.js";
    }
    */
    protected function inclure_meta_donnees_open_graph() {
    }
    
    public function definir_elements() {
      $element = new Entete_Connexion();
      //$nom_club = isset($_GET['n_clb'])? $_GET['n_clb']: "AMP";
      $titre = Site_Web::accede()->sigle();
      $element->def_titre($titre);
      $element->sous_titre = "Resabel";
      $this->ajoute_element_haut($element);
      
      $messages_erreur = new Element_Code();
      $this->ajoute_contenu($messages_erreur);
      
      // formulaire connexion
      $script = "php/scripts/identification_perso.php?c=" . $_GET['c'] . "&s=" . $_GET['s'];
      $action = 'a'; // TODO: je ne sais pas a quoi ca sert ici
      $id = 'form_cnx_perso';
      $formulaire = new Formulaire_Connexion($this, $script, $action, $id);
      $this->ajoute_contenu($formulaire);
    }
  }
  
  // ==========================================================================
?>
