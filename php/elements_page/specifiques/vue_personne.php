<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classes definissant les 'vues' d'un objet de la classe Personne
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : bootstratp 4.x, variables de SESSION
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 04-mar-2019 pchevaillier@gmail.com
  // revision : 11-mar-2019 pchevaillier@gmail.com id_club
  // revision : 16-mar-2019 pchevaillier@gmail.com Menu_Actions_Personne
  // revision : 29-mar-2019 pchevaillier@gmail.com Menu_Actions_Membre
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // -
  // a faire :
  // - titre et id de l'element
  // - completer les actions du menu
  // ==========================================================================
  
  require_once 'php/metier/membre.php';
  require_once 'php/elements_page/generiques/element.php';
  
  
  class Afficheur_Telephone {
    
    public function formatter($numero) {
      $tel = array();
      $bon_separateur = ".";
      $mauvais_separateurs = array(" ", "-");
      $resultat = "";
      if (strlen($numero) === 0) {
        return "";
      } elseif (strlen($numero) === 10) {
        $tel[0] = substr($numero, -10, 2);
        $tel[1] = substr($numero, -8, 2);
        $tel[2] = substr($numero, -6, 2);
        $tel[3] = substr($numero, -4, 2);
        $tel[4] = substr($numero, -2, 2);
      }  elseif (strlen($numero) === 14) {
        $x = str_replace($mauvais_separateurs, $bon_separateur, $numero);
        $tel = explode($bon_separateur, $x);
      }
      $resultat = $tel[0] . $bon_separateur
      . $tel[1] . $bon_separateur
      . $tel[2] . $bon_separateur
      . $tel[3] . $bon_separateur
      . $tel[4];
      return $resultat;
    }
  }
  
  class Afficheur_Nom {
    private $personne = null;
    public function def_personne($p) {
      $this->personne = $p;
    }
    
    public function formatter() {
      $code_html = "<ul class=\"list-inline\"><li class=\"list-inline-item\">" . $this->personne->prenom . "</li><li class=\"list-inline-item\">" . $this->personne->nom . "</li></ul>";
      return $code_html;
    }
  }
  
  // --------------------------------------------------------------------------
  class Afficheur_Courriel_Actif {
    private $personne = null;
    public function def_personne($p) {
      $this->personne = $p;
    }
    
    public function formatter($message, $sujet) {
      $club = isset($_SESSION['id_clb']) ? $_SESSION['id_clb'] : "";
      $signature = isset($_SESSION['n_usr']) ? $_SESSION['n_usr'] : $club;
      $code_html = "<a href=\"mailto:" . $this->personne->courriel . "?subject=" . $club . " Pour " . $this->personne->prenom . " " . $this->personne->nom . " : " . $sujet . "&body=Bonjour " . $this->personne->prenom . ",%0D%0A%0D%0A " . $message . "%0D%0A%0D%0A" . $signature . "\">" . $this->personne->courriel . "</a>";
      return $code_html;
    }
  }
  
  // --------------------------------------------------------------------------
  class Menu_Actions_Personne extends Element {
    public $personne;
    
    public function initialiser() {
    }
    
    protected function afficher_debut() {
      echo '<div class="dropdown"><button class="btn  btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>';
      echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
    }
    
    protected function afficher_actions() {
      echo '<a class="dropdown-item" href="#">Afficher</a>';
      if (isset($_SESSION['adm']))
        echo '<a class="dropdown-item" href="membre.php?mbr=' . $this->personne->code() . '">Modifier</a>';
      if (isset($_SESSION['prs']) && isset($_SESSION['usr']) && $this->personne->code() == $_SESSION['usr'])
        echo '<a class="dropdown-item" href="membre.php?mbr=' . $this->personne->code() . '">Modifier mes données</a>';
    }
    protected function afficher_corps() {
      $this->afficher_actions();
    }
    
    protected function afficher_fin() {
      echo "</div></div>\n";
    }
    
  }
  
  // --------------------------------------------------------------------------
  class Menu_Actions_Membre extends Menu_Actions_Personne {
    
    protected function afficher_actions() {
      parent::afficher_actions();
      if (isset($_SESSION['adm'])) {
        if (!$this->personne->est_chef_de_bord())
          echo '<a class="dropdown-item" href="#">Passer chef de bord</a>';
           
        if ($this->personne->est_actif())
          echo '<a class="dropdown-item" href="#">Désactiver compte</a>';
        else
          echo '<a class="dropdown-item" href="#">Réactiver compte</a>';
        }
    }
   }
     
  // ==========================================================================
?>
