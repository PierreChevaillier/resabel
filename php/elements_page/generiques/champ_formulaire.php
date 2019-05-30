<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : definition des classes de champs de formulaire (classiques)
  //               = elements d'un formulaire simple (classe Formulaire)
  // utilisation : php - require_once <nom_-fichier.php'
  // dependances : classe Formulaire, scripts javascripts, JQuery UI
  // teste avec : PHP 5.5.3 sur Mac OS 10.11 ;
  //              PHP 7.1 sur Mac OS 10.14  (depuis 14-oct-2018)
  //              PHP 7.0 sur hebergeur web
  // Copyright (c) 2017-2019 AMP. Tous droits reserves.
  // ------------------------------------------------------------------------
  // creation : 21-oct-2017 pchevaillier@gmail.com
  // revision : 04-fev-2018 pchevaillier@gmail.com mise en forme, champ montant
  // revision : 10-fev-2018 pchevaillier@gmail.com autres champs
  // revision : 11-fev-2018 pchevaillier@gmail.com valeur, obligatoire
  // revision : 26-aug-2018 pchevaillier@gmail.com Resabel V2 element -> page (web)
  // revision : 14-oct-2018 pchevaillier@gmail.com class Champ_Mot_Passe
  //            id dans Element ; suppression utlisation grille bootstrap
  // revision : 29-dec-2018 pchevaillier@gmail.com gestion erreurs Champ_Nom
  // revision : 30-avr-2019 pchevaillier@gmail.com Choix, Groupe_Choix
  // revision : 11-mai-2019 pchevaillier@gmail.com portabilite Champ_Date
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
    protected $valeur;
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
    
    // --- Proprietes relative au controle de la saisie du champ
    private $obligatoire = False;
    public function obligatoire() { return $this->obligatoire; }
    public function def_obligatoire($valeur = True) { $this->obligatoire = $valeur; }
    
    public $script_controle = "";
    public $fonction_controle_saisie = "";
    
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
      $marque = ($this->obligatoire())? "*": "";
      //echo "\n" . '<div class="form-group"><label class="control-label col-sm-2" for="' . $this->id() . '">' . $this->titre() . ' ' . $marque . '</label><div class="col-sm-10">';
      echo "\n" . '<div class="form-group"><label class="control-label" for="' . $this->id() . '">' . $this->titre() . ' ' . $marque . '</label><div>';
    }
 
    protected function afficher_ouverture_commune() {
      echo "\n<" .  $this->balise . ' class="form-control" ';
      echo ' id="' . $this->id() . '" name="' . $this->id() . '" ';
      if ($this->obligatoire()) echo 'required ';
    }
    
    protected function afficher_fin () {
      echo "</div></div>\n";
    }
    
  }
  
  // --------------------------------------------------------------------------
  // Champs de type ou role particulier

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
    protected function afficher_corps () {
      $this->afficher_ouverture_commune();
      echo 'type="text" ';
      if (strlen($this->fonction_controle_saisie) > 0)
        echo 'onchange="' . $this->fonction_controle_saisie . '(this)" ';
      if (isset($_GET['i']) && ($_GET['i'] == $this->id()) && isset($_GET['v']))
          $this->def_valeur($_GET['v']);
      $affiche = ($this->valeur_definie())? 'value="' . $this->valeur() . '" ' : '';
      echo $affiche . ' >';
      if (isset($_GET['r']) && isset($_GET['i']) && ($_GET['i'] == $this->id())) {
        $msg = "<p id=\"" . $this->id() . "_msg\" class=\"text-danger\">Erreur : ";
        if ($_GET['r'] == 1)
          $msg = $msg . "ce " . $this->titre() . " est trop court";
        elseif ($_GET['r'] == 2)
          $msg = $msg . "ce " . $this->titre() . " contient des caractères non autorisés";
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
  class Champ_Mot_Passe extends Champ_formulaire {
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
      // utulisation du selectionneur de date de JQuery UI
      echo "\n<script type=\"text/javascript\">";
      echo "$(function() { if ($('[type=\"date\"]').prop('type') != 'date' ) { $('[type=\"date\"]').datepicker(); }});\n";
      echo "</script>\n";
      parent::afficher_debut();
    }
  
    protected function afficher_corps () {
      $this->afficher_ouverture_commune();
      if (strlen($this->fonction_controle_saisie) > 0)
        echo 'onchange="' . $this->fonction_controle_saisie . '(this)" ';
      echo ' type="date" ';
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
  
  // --------------------------------------------------------------------------
  class Champ_Binaire extends Champ_Formulaire {
    public $texte = "";
    protected function afficher_corps () {
      echo '<div class="checkbox">';
      echo '<input class="form-control" ';
      echo ' id="' . $this->id() . '" name="' . $this->id() . '" ';
      echo ' type="checkbox" />' . $this->texte . '<br /></div>';
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
