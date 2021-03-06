<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classes definissant les 'vues' d'un objet de la classe Permanence
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : bootstratp 4.x
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 30-mai-2019 pchevaillier@gmail.com
  // revision : 13-jun-2019 pchevaillier@gmail.com affichage responsable
  // --------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // ==========================================================================
  
  // --------------------------------------------------------------------------
  require_once 'php/metier/permanence.php';
  require_once 'php/elements_page/generiques/element.php';
  require_once 'php/elements_page/specifiques/vue_personne.php';
  require_once 'php/metier/calendrier.php';
  // --------------------------------------------------------------------------
  class Afficheur_Permanence extends Element {
    
    public $permanence;
    public function def_permanence(Permanence $objet) {
      $this->permanence = $objet;
    }
    
    public function __construct($page) {
        $this->def_page($page);
    }
    
    public function initialiser() { }
    
    protected function afficher_debut() {
      echo "\n<div style=\"padding:10px\">\n";
    }
    
    protected function afficher_jour() {
      echo "<p>semaine " . $this->permanence->semaine() .  "<br /> " . $this->permanence->jour_texte();
    }
    
    protected function afficher_responsable() {
      $personne = $this->permanence->responsable();
      if (!isset($personne)) return;
      
      $presentation_nom = new Afficheur_Nom();
      $presentation_nom->def_personne($personne);
      $prenom_nom = $presentation_nom->formatter();
      
      $presentation_tel = new Afficheur_telephone();
      $telephone = $presentation_tel->formatter($this->permanence->responsable()->telephone);
      
      $presentation_courriel = new Afficheur_Courriel_Actif();
      $presentation_courriel->def_personne($personne);
      $sujet_courriel = "Permanence";
      $message_courriel = "Je te contacte car tu es de permanence cette semaine.";
      $courriel = $presentation_courriel->formatter($message_courriel, $sujet_courriel);
      
      $contact = "<ul class=\"list-inline\"><li class=\"list-inline-item\">" . $telephone . "</li><li class=\"list-inline-item\">" . $courriel . "</li></ul>";
      echo $prenom_nom . $contact;
    }
    
    protected function afficher_corps() {
      echo "<div class=\"row\">\n";
      echo "<div class=\"col-sm-6\" style=\"font-size:24px\">";
      $this->afficher_jour();
      echo "</div>\n";
      echo "<div class=\"col-sm-6\" style=\"text-align:right;font-size:24px\">";
      $this->afficher_responsable();
      echo "</div>\n";
      echo "</div>\n";
    }
      
    protected function afficher_fin() {
      echo "</div>\n";
    }
  }
  
  class Afficheur_Responsable_Permanence extends Afficheur_Permanence {
    
    protected function afficher_corps() {
      $this->afficher_responsable();
    }
  }
  
  // ==========================================================================
?>
