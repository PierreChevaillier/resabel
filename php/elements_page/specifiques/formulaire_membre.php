<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : Definition de la classe Formulaire_Membre
  // copyright (c) 2018 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <nom_-fichier.php>
  // dependances :
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 06-dec-2018 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // - 
  // a faire :
  // - script controle saisie
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/formulaire.php';
  require_once 'php/elements_page/generiques/champ_formulaire.php';
  
  require_once 'php/metier/personne.php';
  // ==========================================================================
  class Formulaire_Membre extends Formulaire {
    
    public function __construct($page) {
      //$this->def_titre("Connexion");
      $this->message_bouton_validation = "Validation";
      $script_traitement = "php/scripts/membre_saisie_verif.php";
      $action = 'a';
      $id = 'form_mbr';
      parent::__construct($page, $script_traitement, $action, $id);
    }
    
    public function initialiser() {
      $item = null;
      try {
        $item = new Champ_Identifiant("id");
        $this->ajouter_champ($item);
        
        $item = new Champ_Nom("prenom", "js/controle_saisie_nom.js", "verif_nom");
        $item->def_titre("Prénom");
        $item->def_obligatoire();
        $this->ajouter_champ($item);
        
        $item = new Champ_Nom("nom", "js/controle_saisie_nom.js", "verif_nom");
        $item->def_titre("Nom");
        $item->def_obligatoire();
        $this->ajouter_champ($item);
        
        $item = new Champ_Courriel("courriel", "js/controle_saisie_courriel.js", "verif_courriel");
        $item->def_titre("Adresse courriel");
        $this->ajouter_champ($item);
        
        $item = new Champ_Telephone("tel", "js/controle_saisie_telephone.js", "verif_numero_telephone");
        $item->def_titre("Numéro de téléphone");
        $this->ajouter_champ($item);
        
        $item = new Champ_Date("nais");
        $item->def_titre("Date de naissance");
        $this->ajouter_champ($item);
        
        $item = new Champ_Selection("cmn");
        $item->def_titre("Commune");
        $item->valeurs_multiples = False;
        /*
        $item->options = Annonce_Bateau::$courses;
        $this->ajouter_champ($item);
        */
        
        $item = new Champ_Texte("lic");
        $item->def_titre("Licence");
        $this->ajouter_champ($item);
        
        parent::initialiser();
      } catch(Exception $e) {
        die('Exception dans la methode initialiser de la classe Formulaire_Membre : ' . $e->getMessage());
      }
    }
    
    public function initialiser_champs($personne) {
      $this->champ('id')->def_valeur($personne->identifiant);
      $this->champ('prenom')->def_valeur($personne->prenom);
      $this->champ('nom')->def_valeur($personne->nom);
      $this->champ('courriel')->def_valeur($personne->courriel);
      $this->champ('tel')->def_valeur($personne->telephone);
      $this->champ('nais')->def_valeur($personne->date_naissance);
      $this->champ('lic')->def_valeur($personne->num_licence);
    }
  }
  // ==========================================================================
?>
