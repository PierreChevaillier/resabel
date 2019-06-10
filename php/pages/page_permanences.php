<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : Definition de la classe Page_Permanences
  //               Informations sur les personnes de permanence
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin-fichier.php'
  // dependances :
  // teste avec : PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 30-mai-2019 pchevaillier@gmail.com
  // revision : 04-jun-2019 pchevaillier@gmail.com
  // --------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/entete_contenu_page.php';
  require_once 'php/elements_page/generiques/entete_section.php';
  //require_once 'php/elements_page/generiques/modal.php';
  require_once 'php/elements_page/specifiques/page_menu.php';
  
  require_once 'php/metier/permanence.php';
  require_once 'php/bdd/enregistrement_permanence.php';
  require_once 'php/elements_page/specifiques/vue_permanence.php';
  require_once 'php/elements_page/specifiques/table_permanences.php';

  // --------------------------------------------------------------------------
  class Page_Permanences extends Page_Menu {
    
    private $table = null;
    private $enregistrement_permanence = null;
    
    public function definir_elements() {
      
      parent::definir_elements();
      
      $element = new Entete_Contenu_Page();
      $element->def_titre("Permanences");
      $this->ajoute_element_haut($element);
    
      $perm_semaine = null;
      Permanence::cette_semaine($perm_semaine);
      
      $this->enregistrement_permanence = new Enregistrement_Permanence();
      $this->enregistrement_permanence->def_permanence($perm_semaine);
      $existe = $this->enregistrement_permanence->lire();
      if ($existe) {
        $afficheur_permanence = new Afficheur_Permanence($this);
        $afficheur_permanence->permanence = $this->enregistrement_permanence->permanence;
        $this->ajoute_contenu($afficheur_permanence);
      }
      
      $element = new Entete_Section();
      $element->def_titre("Calendrier des permanences");
      $this->ajoute_contenu($element);
      
      $this->table = new Table_Permanences($this);
      $this->ajoute_contenu($this->table);
  
    }
 
    public function initialiser() {
      $futures_permanences = null;
      $this->enregistrement_permanence->collecter_futures($futures_permanences);
      $this->table->def_elements($futures_permanences);
      parent::initialiser();
    }
    
   }
  // ==========================================================================
?>
