<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : Definition de la classe Page_Support_Activite
  //               Informations sur un support d'activite
  // copyright (c) 2018-2020 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <nom_fichier.php>
  // dependances : parametres du script appelant
  //               classes derivees de Support_Activite
  // teste avec : PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 30-aug-2020 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // - en evolution
  // attention :
  // -
  // a faire :
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/specifiques/page_menu.php';
  require_once 'php/elements_page/generiques/element.php';
  require_once 'php/elements_page/generiques/entete_contenu_page.php';
  
  require_once 'php/metier/support_activite.php';
  require_once 'php/elements_page/specifiques/formulaire_support_activite.php';
  require_once 'php/bdd/enregistrement_support_activite.php';
  
  require_once 'php/metier/site_activite.php';
  
  // --------------------------------------------------------------------------
  class Page_Support_Activite extends Page_Menu {
    
    private $form = null;
    
    public function definir_elements() {
      
      parent::definir_elements();
      
      // --- Creation & configuration des elements de la page
      //     contextuelle selon le type d'action
      
      // type d'action
      $creation =  (isset($_GET['a']) && $_GET['a'] == 'c');
      $modification = (isset($_GET['a']) && $_GET['a'] == 'm');
      
      // objet de l'action (ici le type de support)
      $est_bateau = (isset($_GET['typ']) && $_GET['typ'] == 'bat');
      $est_ergo = (isset($_GET['typ']) && $_GET['typ'] == 'erg');
      
      // identifiant de la cible (identifiant du support, si existe)
      $code_support = isset($_GET['sua']) ? $_GET['sua'] : 0;
      
      // Cadre entete page
      // indique la fonction de la page
      $erreur = false;
      $element = new Entete_Contenu_Page();
      $action = "";
      if ($creation) $action = "Nouveau ";
      elseif ($modification) $action = "Informations ";
      else { $action = "Type d'action inconnue"; $erreur = true; }
      $sujet = "";
      if ($est_bateau) $sujet = "bateau";
      elseif ($est_ergo) $sujet = "équipement de salle";
      else { $sujet = "Type de support inconnu"; $erreur = true; }
      $element->def_titre($action . $sujet);
      $this->ajoute_element_haut($element);
      if ($erreur) return;
      
      // Initialisation des informations sur le support
      $support = null;
      
      if ($modification) {
        // On va initialiser le formulaire avec les informations enregistrees
        // dans la base de donnees
        $enregistrement = new Enregistrement_Support_Activite();
        $trouve = $enregistrement->lire($code_support);
        if ($trouve)
          $support = $enregistrement->support_activite();
        else return;
      } elseif ($creation) {
        if ($est_bateau) {
          $support = new Bateau($code_support);
          // ATTENTION : adhoc AMP POUR DEBUTER (1 seul site de chaque type)
          // TODO : a rendre plus generique
          $support->site_base = new Site_Activite_Mer(1);
          $support->pour_competition = false;
          $support->pour_loisir = true;
        } elseif ($est_ergo) {
          $support = new Plateau_Ergo($code_support);
          $support->site_base = new Salle_Sport(2);
        }
        // initialisations (proprietes communes aux differentes classes)
        $support->pour_competition = false;
        $support->pour_loisir = true;
      }
      
      // Ceation du formulaire pour la modification des informations
      
      $formulaire = new Formulaire_Support_Activite($this, $support);
      $this->ajoute_contenu($formulaire);
      $this->form = $formulaire;
      
    }
    
    public function initialiser() {
      parent::initialiser();

      if ($this->form)
        $this->form->initialiser_champs();
      
    }
  }
  // ==========================================================================
?>
