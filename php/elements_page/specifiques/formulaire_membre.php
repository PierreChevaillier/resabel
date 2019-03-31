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
  
  require_once 'php/metier/membre.php';
  require_once 'php/metier/calendrier.php';
  
  require_once 'php/bdd/enregistrement_commune.php';
  
  // ==========================================================================
  class Formulaire_Membre extends Formulaire {
    
    private $membre = null;
    
    public function __construct($page, $membre) {
      //$this->def_titre("Connexion");
      $this->membre = $membre;
      $this->message_bouton_validation = "Valider";
      $this->confirmation_requise = true;
      $info_mbr = isset($this->membre) ? '?mbr=' . $this->membre->code() : '';
      $script_traitement = 'php/scripts/membre_info_maj.php' . $info_mbr;
      $action = 'a';
      $id = 'form_mbr';
      
      parent::__construct($page, $script_traitement, $action, $id);
    }
    
    public function initialiser() {
      
      $item = null;
      try {
        $item = new Champ_Identifiant("id");
        $item->def_titre("Identifiant de connexion");
        $item->def_obligatoire();
        $this->ajouter_champ($item);
        
        $item = new Champ_Civilite("gnr");
        $item->def_titre("Civilité");
        $item->def_obligatoire();
        $this->ajouter_champ($item);
        
        $item = new Champ_Nom("prn", "js/controle_saisie_nom.js", "verif_nom");
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
        
        $item = new Champ_Selection("cmn");
        $item->def_titre("Commune");
        $item->valeurs_multiples = False;
        $communes = array();
        Enregistrement_Commune::collecter("acces = 'O'"," nom ASC", $communes);
        foreach ($communes as $code => $c)
          $item->options[$code] = $c->nom();
        $this->ajouter_champ($item);
        
        $item = new Champ_Date("nais");
        $item->def_titre("Date de naissance");
        $this->ajouter_champ($item);
        
        $item = new Champ_Texte("lic");
        $item->def_titre("Licence");
        $this->ajouter_champ($item);
        
        parent::initialiser();
      } catch(Exception $e) {
        die('Exception dans la methode initialiser de la classe Formulaire_Membre : ' . $e->getMessage());
      }
    }
    
    public function initialiser_champs() {
      $ok = isset($this->membre);
      if ($ok) {
        $this->champ('id')->def_valeur($this->membre->identifiant);
        $this->champ('gnr')->def_valeur($this->membre->genre);
        $this->champ('prn')->def_valeur($this->membre->prenom);
        $this->champ('nom')->def_valeur($this->membre->nom);
        $this->champ('courriel')->def_valeur($this->membre->courriel);
        $this->champ('tel')->def_valeur($this->membre->telephone);
        $this->champ('cmn')->def_valeur($this->membre->code_commune);
        
        if ($this->membre->date_naissance) {
          $cal = new Calendrier();
          $val_date = $cal->date_html($this->membre->date_naissance);
          $this->champ('nais')->def_valeur($val_date);
        }
        $this->champ('lic')->def_valeur($this->membre->num_licence);
      }
      return $ok;
    }
  }
  // ==========================================================================
?>
