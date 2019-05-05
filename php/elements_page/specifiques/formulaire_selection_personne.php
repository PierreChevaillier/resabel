<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : formulaire de selection des personnes a afficher
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_fichier.php>
  // dependances :
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 29-mar-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // - 
  // a faire :
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/formulaire.php';
  require_once 'php/elements_page/generiques/champ_formulaire.php';
  
   require_once 'php/bdd/enregistrement_commune.php';
  
  // ==========================================================================
  class Formulaire_Selection_Personne extends Formulaire {
    
    public function __construct($page) {
      $this->def_titre("Critères de sélection");
      $this->message_bouton_validation = "Afficher sélection";
      // personnes.php?a=l&act=1&cnx=1
      $this->methode = 'post';
      $script_traitement = "personnes.php?a=l&act=" . $_GET['act'] . "&cnx=" . $_GET['cnx'];
      $action = 'm';
      $id = 'form_sel_prs';
      $page->javascripts[] = 'js/raz_formulaire.js';
      parent::__construct($page, $script_traitement, $action, $id);
    }
    
    public function initialiser() {
      $item = null;
      try {
        $item = new Champ_Nom("prn", "js/controle_saisie_nom.js", "verif_nom");
        if (isset($_POST['prn']) && $_POST['prn'] != "")
          $item->def_valeur($_POST['prn']);
        $item->def_titre("Prénom (début)");
        $this->ajouter_champ($item);
        
        $item = new Champ_Nom("nom", "js/controle_saisie_nom.js", "verif_nom");
        $item->def_titre("Nom (début)");
        if (isset($_POST['nom']) && $_POST['nom'] != "")
          $item->def_valeur($_POST['nom']);
        $this->ajouter_champ($item);
        
        $item = new Champ_Selection("cmn");
        $item->def_titre("Commune");
        $item->valeurs_multiples = false;
        $communes = array();
        Enregistrement_Commune::collecter("acces = 'O'"," nom ASC", $communes);
        $item->options[0] = "Toutes les communes";
        foreach ($communes as $code => $c)
          $item->options[$code] = $c->nom();
        if (isset($_POST['cmn']))
          $item->def_valeur($_POST['cmn']);
        $this->ajouter_champ($item);

        $item = new Champ_Selection('cdb');
        $item->def_titre("Chef.fe de bord");
        $item->options = array(0 => 'Tout le monde', 1 => 'Oui', 2 => 'Non');
        if (isset($_POST['cdb']))
          $item->def_valeur($_POST['cdb']);
        $this->ajouter_champ($item);
        
        $item = new Champ_Selection('niv');
        $item->def_titre("Expérience");
        $item->options = array(0 => 'Tout le monde', 1 => 'Débutant.e.s', 2 => 'Confirmé.e.s');
        if (isset($_POST['niv']))
          $item->def_valeur($_POST['niv']);
        $this->ajouter_champ($item);
        
        parent::initialiser();
      } catch(Exception $e) {
        die('Exception dans la methode initialiser de la classe Formulaire_Selection_Personne : ' . $e->getMessage());
      }
    }
    
    protected function afficher_corps() {
      parent::afficher_corps();
      
      echo '<div class="form-group form-btn" id="btn_raz" >';
      echo '<input type="button" class="btn btn-large btn-outline-secondary"';
      echo ' onclick="return raz_valeurs_formulaire(' . $this->id() . ')"';
      echo ' value="Supprimer les critères de sélection" >';
      echo '</div>';
      
    }
  }
  // ==========================================================================
?>
