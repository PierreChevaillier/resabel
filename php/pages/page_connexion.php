<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : Definition de la classe Page_Connexion
  // copyright (c) 2018 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <nom_-fichier.php'
  // dependances :
  // teste avec : PHP 5.5.3 sur Mac OS 10.11 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 17-jun-2018 pchevaillier@gmail.com
  // revision :
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
  
  // -------------------------------------------------------------------------
  class Page_Connexion extends Page {
    
    private $informations_temporaires = array();
    public function ajoute_information($element) {
      $this->contenus[] = $element;
      $element = $this;
    }

    public function __construct($nom_site, $nom_page) {
      parent::__construct($nom_site, $nom_page);
      $this->javascripts[] = "scripts/controle_identification.js";
      $this->javascripts[] = "scripts/md5.js";
    }
    
    protected function inclure_meta_donnees_open_graph() {
    }
    
    public function definir_elements() {
      $element = new Entete_Connexion();
      $element->def_titre("AMP <br /> Accès à Resabel");
      $this->ajoute_element_haut($element);
      foreach ($this->informations_temporaires as $info) {
        $this->ajoute_contenu($info);
      }
      
      $messages_erreur = new Element_Code();
      $page->ajoute_contenu($messages_erreur);
      
      // infos temporaires
      // formulaire connexion
      
    }
  }
  
  // ==========================================================================
?>
