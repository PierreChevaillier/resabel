<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : definition des classes de champs de formulaire (classiques)
  //               = elements d'un formulaire simple (classe Formulaire)
  // utilisation : php - require_once <nom_-fichier.php'
  // dependances : classe Formulaire, scripts javascripts, JQuery UI
// bootstrap 5.x
  // teste avec : PHP 5.5.3 sur Mac OS 10.11 ;
  //              PHP 7.1 sur Mac OS 10.14  (depuis 14-oct-2018)
  //              PHP 7.3 sur hebergeur web
  //              PHP 8.1 sur macOS 12.5 (depuis 21-dec-2022)
  // Copyright (c) 2017-2024 AMP. Tous droits reserves.
  // ------------------------------------------------------------------------
  // creation : 21-oct-2017 pchevaillier@gmail.com
  // revision : 04-fev-2018 pchevaillier@gmail.com mise en forme, champ montant
  // revision : 10-fev-2018 pchevaillier@gmail.com autres champs
  // revision : 26-aug-2018 pchevaillier@gmail.com Resabel V2 element -> page (web)
  // revision : 14-oct-2018 pchevaillier@gmail.com class Champ_Mot_Passe
  //            id dans Element ; suppression utlisation grille bootstrap
  // revision : 29-dec-2018 pchevaillier@gmail.com gestion erreurs Champ_Nom
  // revision : 30-avr-2019 pchevaillier@gmail.com Choix, Groupe_Choix
  // revision : 11-mai-2019 pchevaillier@gmail.com portabilite Champ_Date
  // revision : 04-jan-2020 pchevaillier@gmail.com Champ de type hidden
  // revision : 13-sep-2020 pchevaillier@gmail.com Champ_Binaire (checked)
  // revision : 19-sep-2020 pchevaillier@gmail.com code erreur, Champ_Entier_Naturel
  // revision : 21-dec-2022 pchevaillier@gmail.com init valeur (php 8.x)
  // revision : 23-feb-2023 pchevaillier@gmail.com on_change pour Champ_Identifiant
// revision : 31-jan-2024 pchevaillier@gmail.com bootstrap 5.x
// revision : 13-mai-2024 pchevaillier@gmail.com Champ_Heure
// revision : 04-jun-2024 pchevaillier@gmail.com *Champ_Nom
  // ------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire : ce n'est pas tres SOLID, ni DRY...
  // ------------------------------------------------------------------------

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/element.php';
  
  // --------------------------------------------------------------------------
  // --- Definition de la classe

  abstract class Champ_Formulaire extends Element {
    protected $balise = 'input';
    
    // --- Proprietes relatives à la valeur du champ.
    protected string $valeur = '';
    public function valeur() {
      return $this->valeur;
    }
    
    public function valeur_definie() {
      return strlen($this->valeur) > 0;
    }
    
    public function def_valeur($valeur) {
      $this->valeur = $valeur;
      return True;
    }
    
    // Texte (d'aide)
    public $texte_aide = "";
    
    public $desactive = false;
    
    // --- Proprietes relative au controle de la saisie du champ
    private $obligatoire = False;
    public function obligatoire() { return $this->obligatoire; }
    public function def_obligatoire($valeur = True) { $this->obligatoire = $valeur; }
    
    public $script_controle = "";
    public $fonction_controle_saisie = "";
    
    // Champ 'hidden'
    private $cache = False;
    public function cache() { return $this->cache; }
    public function def_cache($valeur = True) { $this->cache = $valeur; }
    
    //  --- Constructeurs
    public function __construct($id, $script = "", $fonction = "") {
      $this->def_id($id);
      $this->script_controle = $script;
      $this->fonction_controle_saisie = $fonction;
    }
    
    // --- Definition des operations abstraites de la super-classe
    public function initialiser() {
      if ((strlen($this->script_controle) > 0) && ($this->page))
        $this->page->javascripts[] = $this->script_controle;
    }
    
    protected function afficher_debut() {
      if (!$this->cache()) {
        $marque = ($this->obligatoire())? "*": "";
        echo '<div class="mt-1">';
     
        echo "\n" . '<label class="form-label" for="' . $this->id() . '">'
          . $this->titre() . ' ' . $marque . '</label>';
      }
    }
 
    protected function afficher_ouverture_commune() {
      echo "\n<" .  $this->balise;
      if (!$this->cache()) echo ' class="form-control" ';
      echo ' id="' . $this->id() . '" name="' . $this->id() . '" ';
      if  (!$this->cache() && $this->obligatoire())
        echo 'required ';
    }
    
    protected function afficher_fin () {
      if (!$this->cache()) {
        if (strlen($this->texte_aide) > 0)
          echo '<div id="' . $this->id() . '_aide" class="form-text">'
            . $this->texte_aide . '</div>';
        echo '</div>';
        }
    }
    
  }
  
  // --------------------------------------------------------------------------
  // Champs de type ou role particulier

  class Champ_Cache extends Champ_Formulaire {
    public function __construct($id, $script = "", $fonction = "") {
      $this->def_cache(True);
      parent::__construct($id, $script, $fonction);
    }
    
    protected function afficher_corps () {
      $this->afficher_ouverture_commune();
      echo ' type="hidden" value="' . $this->valeur() . '" />' . PHP_EOL;
    }
  }
  
  // --------------------------------------------------------------------------
  class Champ_Selection extends Champ_Formulaire {
    
    public $valeurs_multiples = False;
    public $options = array();
    
    public function __construct($id, $script = "", $fonction = "") {
      parent::__construct($id, $script, $fonction);
      $this->balise = 'select';
    }
    
    protected function afficher_corps () {
      $this->afficher_ouverture_commune();
      if ($this->valeurs_multiples) echo ' multiple ';
      if (strlen($this->fonction_controle_saisie) > 0)
        echo 'onchange="' . $this->fonction_controle_saisie . '" ';
      echo '>';
      foreach ($this->options as $valeur => $texte) {
        $selection = ($this->valeur() == $valeur)? ' selected' : '';
        echo '<option value="' . $valeur . '"' . $selection . '>' . $texte . '</option>';
      }
      echo '</select>';
    }
  }
    
  // --------------------------------------------------------------------------
  class Champ_Civilite extends Champ_Selection {
    
    public function initialiser() {
      parent::initialiser();
      $this->options = array('F' => 'Madame', 'H' => 'Monsieur');
      
    }
  }

  // --------------------------------------------------------------------------
  class Champ_Telephone extends Champ_Formulaire {
    protected function afficher_corps () {
      $this->afficher_ouverture_commune();
      echo 'type="tel" size="15" maxlength="14" ';
      if (strlen($this->fonction_controle_saisie) > 0)
          echo 'onchange="' . $this->fonction_controle_saisie . '(this)" ';
      $affiche = ($this->valeur_definie())? 'value="' . $this->valeur() . '" ' : 'placeholder="0601020304" ';
      echo $affiche . ' />';
    }
  }
 
  // --------------------------------------------------------------------------
  class Champ_Courriel extends Champ_Formulaire {
    protected function afficher_corps () {
      $this->afficher_ouverture_commune();
      echo 'type="mail" ';
      if (strlen($this->fonction_controle_saisie) > 0)
        echo 'onchange="' . $this->fonction_controle_saisie . '(this)" ';
      $affiche = ($this->valeur_definie())? 'value="' . $this->valeur() . '"' : 'placeholder="xxx@yyy.zz" ';
      echo $affiche . ' />';
    }
  }
  
  // --------------------------------------------------------------------------
  class Champ_Nom extends Champ_Formulaire {
    public int $nb_car_min = 0;
    public int $nb_car_max = 0;
    
    protected function afficher_corps () {
      $this->afficher_ouverture_commune();
      echo 'type="text" ';
      if (strlen($this->fonction_controle_saisie) > 0)
        echo 'onchange="' . $this->fonction_controle_saisie . '(this)" ';
      if (isset($_GET['i']) && ($_GET['i'] == $this->id()) && isset($_GET['v']))
          $this->def_valeur($_GET['v']);
      $affiche = ($this->valeur_definie())? 'value="' . $this->valeur() . '" ' : '';
      $affiche = $affiche . (($this->nb_car_max > 0)? ' maxLength="' . $this->nb_car_max . '" ' : '');
      $affiche = $affiche . (($this->nb_car_min > 0)? ' minLength="' . $this->nb_car_min . '" ' : '');
      echo $affiche . ' >';
      if (isset($_GET['r']) && isset($_GET['i']) && ($_GET['i'] == $this->id())) {
        $msg = "<p id=\"" . $this->id() . "_msg\" class=\"text-danger\">Erreur : ";
        if ($_GET['r'] == 1)
          $msg = $msg . "la valeur saisie est trop courte"; //"ce " . $this->titre() . " est trop court";
        elseif ($_GET['r'] == 2)
          $msg = $msg . "la valeur saisie est trop longue"; //"ce " . $this->titre() . " est trop court";elseif ($_GET['r'] == 3)
        elseif ($_GET['r'] == 3)
        $msg = $msg . "la valeur saisie contient des caractères non autorisés"; // "ce " . $this->titre() . " contient des caractères non autorisés";
        else
          $msg = $msg . "saisie incorrecte";
        echo $msg . "</p>";
      }
    }
  }

  // --------------------------------------------------------------------------
  class Champ_Identifiant extends Champ_Formulaire {
    public function __construct($id, $script = "", $fonction = "") {
      parent::__construct($id, $script, $fonction);
      $this->def_titre("Identifiant");
      $this->def_obligatoire();
    }
    
    protected function afficher_message_erreur($code_erreur) {
      $msg = "<p id=\"" . $this->id() . "_msg\" class=\"text-danger\">Erreur : ";
      if ($code_erreur == 1)
        $msg = $msg . $this->titre() . " trop court";
      elseif ($code_erreur == 2)
        $msg = $msg . $this->titre() . " saisi déjà attribué - opération annulée";
      else
        $msg = $msg . "saisie incorrecte";
      echo $msg . "</p>";
    }
    
    protected function afficher_corps () {
      $this->afficher_ouverture_commune();
      if (strlen($this->fonction_controle_saisie) > 0)
        echo 'onchange="' . $this->fonction_controle_saisie . '(this)" ';
      echo 'type="text" maxlength="50" ';
      $affiche = ($this->valeur_definie())? 'value="' . $this->valeur() . '" ' : '';
      echo $affiche . ' />';
      if (isset($_GET['err']) && ($_GET['err'] == 'id'))
        echo "<p class=\"text-danger\">Erreur : identifiant inconnu</p>";
      if (isset($_GET['err']) && ($_GET['err'] == 'cnx'))
        echo "<p class=\"text-danger\">Erreur : connexion impossible avec cet identifiant</p>";
      if (isset($_GET['err']) && ($_GET['err'] == 'act'))
        echo "<p class=\"text-danger\">Connexion impossible : votre compte a été désactivé</p>";
      
      if (isset($_GET['r']) && isset($_GET['i']) && ($_GET['i'] == $this->id()))
        $this->afficher_message_erreur($_GET['r']);
    }
  }
  
  // --------------------------------------------------------------------------
  class Champ_Mot_Passe_Crypte extends Champ_formulaire {
    public function __construct($id, $script, $fonction = 'crypte_mdp') {
      parent::__construct($id, $script, $fonction);
      $this->def_titre("Mot de passe");
      $this->def_obligatoire();
    }
    
    protected function afficher_corps () {
      $this->afficher_ouverture_commune();
      echo 'type="password" ';
      $params = '(this, ' . $this->id() . ', ' . $this->id() . '_crypte)"';
      //echo 'onchange="' . $this->fonction_controle_saisie . $params . ' ';
      $affiche = ($this->valeur_definie())? 'value="' . $this->valeur() . '" ' : '';
      echo $affiche . ' />';
      if (isset($_GET['err']) && ($_GET['err'] == 'mdp'))
        echo "<p class=\"text-danger\">Erreur : mot de passe incorrect</p>";
      echo '<input type="hidden" id="' . $this->id() . '_crypte" name="' . $this->id() . '_crypte" >';
    }
  }
  
  // --------------------------------------------------------------------------
  class Champ_Mot_Passe extends Champ_formulaire {
    public function __construct($id, $script = '', $fonction= '') {
      parent::__construct($id, $script, $fonction);
      $this->def_titre("Mot de passe");
      $this->def_obligatoire();
    }
    
    protected function afficher_corps () {
      $this->afficher_ouverture_commune();
      echo 'type="password" ';
      $affiche = ($this->valeur_definie())? 'value="' . $this->valeur() . '" ' : '';
      echo $affiche . ' />';
      if (isset($_GET['err']) && ($_GET['err'] == 'mdp'))
        echo "<p class=\"text-danger\">Erreur : mot de passe incorrect</p>";
    }
  }
  // --------------------------------------------------------------------------
  class Champ_Texte extends Champ_Formulaire {
    public $longueur_max = 50;
    
    protected function afficher_corps () {
      $this->afficher_ouverture_commune();
      echo ' type="text" size = "' . $this->longueur_max . '" maxlength="' . $this->longueur_max . '" ';
      if (strlen($this->fonction_controle_saisie) > 0)
        echo 'onchange="' . $this->fonction_controle_saisie . '(this)" ';
      $affiche = ($this->valeur_definie())? 'value="' . $this->valeur() . '" ' : '';
      echo $affiche . ' />';
    }
  }

  // --------------------------------------------------------------------------
  class Champ_Date extends Champ_Formulaire {
    
    protected function afficher_debut() {
      // raison : safari et IE (< 12) ne supportent pas le type date
      // utilisation du selectionneur de date de JQuery UI
      echo "\n<script type=\"text/javascript\">";
      echo "$(function() { if ($('[type=\"date\"]').prop('type') != 'date' ) { $('[type=\"date\"]').datepicker(); }});\n";
      echo "</script>\n";
      parent::afficher_debut();
    }
  
    protected function afficher_corps () {
      $this->afficher_ouverture_commune();
      if (strlen($this->fonction_controle_saisie) > 0)
        echo ' onchange="' . $this->fonction_controle_saisie . '(this)" ';
      echo ' type="date" ';
      $affiche = ($this->valeur_definie())? 'value="' . $this->valeur() . '" ' : '';
      echo $affiche . ' />';
    }
  }
  
// --------------------------------------------------------------------------
class Champ_Heure extends Champ_Formulaire {

  protected function afficher_corps () {
    $this->afficher_ouverture_commune();
    if (strlen($this->fonction_controle_saisie) > 0)
      echo ' onchange="' . $this->fonction_controle_saisie . '(this)" ';
    echo ' type="time" ';
    $affiche = ($this->valeur_definie())? 'value="' . $this->valeur() . '" ' : '';
    echo $affiche . ' />';
  }
}

  // --------------------------------------------------------------------------
  class Champ_Zone_Texte extends Champ_Formulaire {
    public $longueur_max = 50;
    public $nombre_lignes = 2;
    public $largeur = 30;
    protected function afficher_corps () {
      echo '<textarea class="form-control"';
      echo ' id="' . $this->id() . '"  name="' . $this->id() . '"';
      echo ' rows= "' . $this->nombre_lignes . '" cols="' . $this->largeur . '" >';
      $affiche = ($this->valeur_definie())? $this->valeur() . ' ' : ' ';
      echo $affiche;
      echo '</textarea>';
    }
  }
  
  // --------------------------------------------------------------------------
  class Champ_Montant extends Champ_Formulaire {
    public $valeur_min = 0;
    public $valeur_max = 1000;
    protected function afficher_corps () {
      echo '<input class="form-control"';
      echo ' id="' . $this->id() . '"  name="' . $this->id() . '"';
      if (strlen($this->fonction_controle_saisie) > 0)
        echo 'onchange="' . $this->fonction_controle_saisie . '(this)" ';
      echo ' type="number" min="' . $this->valeur_min
        . '" max="' . $this->valeur_max . '" value="'
        . $this->valeur_min . '"/>';
    }
  }
 
  class Champ_Entier_Naturel extends Champ_Formulaire {
     public $valeur_min = 0;
     public $valeur_max = PHP_INT_MAX;
     protected function afficher_corps () {
       $val = $this->valeur();
       $val = max($this->valeur_min, $val);
       $val = min($this->valeur_max, $val);
       $this->def_valeur($val);
       echo '<input class="form-control"';
       echo ' id="' . $this->id() . '"  name="' . $this->id() . '"';
       if (strlen($this->fonction_controle_saisie) > 0)
         echo 'onchange="' . $this->fonction_controle_saisie . '(this)" ';
       echo ' type="number" min="' . $this->valeur_min
         . '" max="' . $this->valeur_max . '" value="'
         . $this->valeur() . '"/>';
     }
   }
  
  // --------------------------------------------------------------------------
  class Champ_Binaire extends Champ_Formulaire {
    public $texte = "";
    protected function afficher_corps () {
      echo '<div class="form-check">';
      echo '<input class="form-check-input" ';
      echo ' id="' . $this->id() . '" name="' . $this->id() . '" ';
      $checked = ($this->valeur() == 1) ? ' checked ' : ' ';
      echo ' type="checkbox" value="' . $this->valeur() . '"' . $checked;
      if ($this->desactive)
        echo ' disabled ';
      echo '>';
        echo '<label class="form-check-label">' . $this->texte . '</label>' ;
      echo '</div>';
    }
  }

  /*
   * tentative infructueuse
   *
  class Choix {
    public $id = '';
    public $valeur = '';
    public $label = '';
    public $actif = false;
  }
  
  class Groupe_Choix extends Champ_Formulaire {
    public $choix = array();
    protected function afficher_corps() {
      echo '<fieldset>';
      foreach ($this->choix as $choix) {
        $selection = ($choix->actif) ? ' checked' : ' ';
        echo '<div><input type="checkbox" id="' . $choix->id
          . '" name="' . $this->id() . '"' . $selection
          . ' value="' . $choix->valeur . '">';
        echo '<label for="'  . $choix->id . '">&nbsp;&nbsp;' . $choix->label
          . '</label></div>';
      }
      echo '</fieldset>';
    }
  }
   */
  // ===========================================================================
?>
