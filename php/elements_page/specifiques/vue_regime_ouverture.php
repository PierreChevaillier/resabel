<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classes definissant les 'vues' d'un objet des classes
  //               derivees de Regime_Ouverture
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : bootstratp 4.x
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 02-jul-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // ==========================================================================
  
  // --------------------------------------------------------------------------
  require_once 'php/metier/regime_ouverture.php';
  require_once 'php/elements_page/generiques/element.php';
  
  // --------------------------------------------------------------------------
  abstract class Afficheur_Regime_Ouverture extends Element {
    
    static function creer(Page $page, Regime_ouverture $objet_metier) {
      $vue = null;
      if (is_a($objet_metier, 'Regime_Diurne'))
        $vue = new Afficheur_Regime_Diurne($page);
      elseif (is_a($objet_metier, 'Regime_Hebdomadaire'))
        $vue = new Afficheur_Regime_Hebdomadaire($page);
      $vue->def_regime($objet_metier);
      return $vue;
    }
    
    private $regime;
    public function def_regime(Regime_ouverture $objet_metier) { $this->regime = $objet_metier; }
    public function regime() { return $this->regime; }
    
    public function __construct($page) {
        $this->def_page($page);
    }
    
    public function initialiser() { }
    
    protected function afficher_debut() {
      echo "\n<div style=\"padding:10px\">\n";
    }
    
    protected function afficher_fin() {
      echo "</div>\n";
    }
  }
  
  // --------------------------------------------------------------------------
  class Afficheur_Regime_Diurne extends Afficheur_Regime_Ouverture {
    
    protected function afficher_corps() {
      $fmt = '%H:%I'; //'%d-%m-%Y %H:%I:%s';
      echo "\n<p>Nom : " , $this->regime()->nom(), "</p>";
      $str = $this->regime()->heure_ouverture->format($fmt);
      echo "\n<p>Heure ouverture : " , $str, "</p>";
      $str = $this->regime()->heure_fermeture->format($fmt);
      echo "\n<p>Heure fermeture : " , $str, "</p>";
      $str = $this->regime()->duree_seance->format($fmt);
      echo "\n<p>Durée séance : " , $str, "</p>";
    }
  }
  
  // ==========================================================================
?>
