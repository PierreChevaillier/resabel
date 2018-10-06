<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : Fichier vide : modele entete
  // copyright (c) 2018 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <nom_-fichier.php>
  // dependances :
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
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
  require_once 'php/elements_page/generiques/formulaire.php';
  require_once 'php/elements_page/generiques/champ_formulaire.php';
  
  // ==========================================================================
  class Formulaire_Connexion extends Formulaire {
    
    public function __construct($page) {
      $this->def_titre("Connexion");
      $this->message_bouton_validation = "Connexion";
      $script_traitement = "";
      $action = 'a';
      $id = 'form_cnx';
      parent::__construct($page, $script_traitement, $action, $id);
    }
    
    public function initialiser() {
      $item = null;
      try {
        $item = new Champ_Identifiant("id");
        $this->ajouter_champ($item);
        
        $item = new Champ_Mot_Passe("mdp");
        $this->ajouter_champ($item);
        
        parent::initialiser();
    } catch(Exception $e) {
      die('Exception dans la methode initialiser de la classe Formulaire_Connexion : ' . $e->getMessage());
    }
    }
  }
  // ==========================================================================
?>
