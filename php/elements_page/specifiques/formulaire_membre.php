<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : Definition de la classe Formulaire_Membre
  //               Formulaire pour la saisie/modification des informations
  //               relatives a un membre du club
  // copyright (c) 2018-2023 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <nom_-fichier.php>
  // dependances :
  // utilise avec :
  // - depuis 2023 :
  //   PHP 8.2 sur macOS 13.x
  //   PHP 8.1 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 06-dec-2018 pchevaillier@gmail.com
  // revision : 27-dec-2019 pchevaillier@gmail.com impact refonte Calendrier
  // revision : 23-feb-2023 pchevaillier@gmail.com + controle saisie identifiant
  // revision : 02-dec-2023 pchevaillier@gmail.com modifs. mineures PHP 8.2
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // - 
  // a faire :
  // - script controle saisie
  // - infotmation visuelle sur champs incorrect
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/formulaire.php';
  require_once 'php/elements_page/generiques/champ_formulaire.php';

  require_once 'php/metier/calendrier.php';
  
  require_once 'php/metier/membre.php';
  require_once 'php/bdd/enregistrement_commune.php';
  
  // ==========================================================================
  class Formulaire_Membre extends Formulaire {
    
    private $membre = null;
    
    public function __construct($page, $membre) {
      //$this->def_titre("Connexion");
      $action = isset($_GET['a']) ? $_GET['a'] : 'undef';
      if (isset($_GET['a']) && $_GET['a'] == 'c') {
        $page->javascripts[] = "js/generer_identifiant_connexion.js";
        $this->message_bouton_validation = "Valider création";
      }
      $this->membre = $membre;
     
      $this->confirmation_requise = true;
      
      // Parametrage de l'appel du script php qui traite
      // les donnees saisies ou modifiees grace au formulaire
      $this->methode = 'post';
      $script_traitement = 'php/scripts/membre_info_maj.php?';
      $params = 'a=' . $_GET['a'] . '&o=' . $_GET['o'];
      if (isset($_GET['a']) && $_GET['a'] == 'm') {
        $params = $params . (isset($this->membre) ? '&mbr=' . $this->membre->code() : '');
        $this->message_bouton_validation = "Valider modification";
      }
      $script_traitement = $script_traitement . $params;
        
      //$action = 'a';
      $id = 'form_mbr';
      
      parent::__construct($page, $script_traitement, $action, $id);
      
    }
    
    public function initialiser() {
      
      $item = null;
      if (isset($_GET['a']) && $_GET['a'] == 'c') {
        $code_chargement = new Element_Code();
        $script = "\n<script>window.onload = function() {creer_gestionnaire_evenement('id', 'prn', 'nom'); };</script>\n";
        $code_chargement->def_code($script);
        $this->page->ajoute_contenu($code_chargement);
      }
      
      try {
    
        $item = new Champ_Cache("mbr"); // on en a besoin pour verification identifiant
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
        
        $item = new Champ_Identifiant("id",
                                      "js/verification_identifiant_membre.js",
                                      "verif_identifiant");
        $item->def_titre("Identifiant de connexion");
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
        $this->champ('mbr')->def_valeur($this->membre->code());
        $this->champ('id')->def_valeur($this->membre->identifiant());
        $this->champ('gnr')->def_valeur($this->membre->genre);
        $this->champ('prn')->def_valeur($this->membre->prenom);
        $this->champ('nom')->def_valeur($this->membre->nom);
        $this->champ('courriel')->def_valeur($this->membre->courriel);
        $this->champ('tel')->def_valeur($this->membre->telephone);
        $this->champ('cmn')->def_valeur($this->membre->code_commune);
        if ($this->membre->date_naissance) {
          $val_date = $this->membre->date_naissance->date_html();
          $this->champ('nais')->def_valeur($val_date);
        }
        $this->champ('lic')->def_valeur($this->membre->num_licence);
      } else {
        die('membre pas defini');
      }
      return $ok;
    }
  }
  // ==========================================================================
?>
