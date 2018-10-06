<?php
  // ========================================================================
  // description : definition des classes de champs de formulaire (classiques)
  // utilisation : elements d'un formulaire simple
  // teste avec  : PHP 5.5.3 sur Mac OS 10.11
  // contexte    : classe Formulaire
  // Copyright (c) 2017-2018 AMP. Tous droits reserves.
  // ------------------------------------------------------------------------
  // creation : 21-oct-2017 pchevaillier@gmail.com
  // revision : 04-fev-2018 pchevaillier@gmail.com mise en forme, champ montant
  // revision : 10-fev-2018 pchevaillier@gmail.com autres champs
  // revision : 11-fev-2018 pchevaillier@gmail.com valeur, obligatoire
  // revision : 26-aug-2018 pchevaillier@gmail.com Resabel V2 element -> page (web)
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
    
    // --- Id et nom
    protected $identifiant = "";
    public function id() {
      return $this->identifiant;
    }
    
    //protected $nom_variable = "";
    
    // --- Proprietes relative au controle de la saisie du champ
    private $obligatoire = False;
    public function obligatoire() { return $this->obligatoire; }
    public function def_obligatoire($valeur = True) { $this->obligatoire = $valeur; }
    
    public $script_controle = "";
    public $fonction_controle_saisie = "";
    
    //  --- Constructeurs
    public function __construct($id, $script = "", $fonction = "") {
      $this->identifiant = $id;
      $this->script_controle = $script;
      $this->fonction_controle_saisie = $fonction;
    }
    
    // --- Defintion des operations abstraites de la super-classe
    public function initialiser() {
      if ((strlen($this->script_controle) > 0) && ($this->page))
        $this->page->javascripts[] = $this->script_controle;
    }
    
    protected function afficher_debut() {
      $marque = ($this->obligatoire())? "*": "";
      echo "\n" . '<div class="form-group"><label class="control-label col-sm-2" for="' . $this->id() . '">' . $this->titre() . ' ' . $marque . '</label><div class="col-sm-10">';
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
      $affiche = ($this->valeur_definie())? 'value="' . $this->valeur() . '" ' : '';
      echo $affiche . ' />';
    }
  }

  // --------------------------------------------------------------------------
  class Champ_Identifiant extends Champ_Formulaire {
    public function __construct($id, $script = "", $fonction = "") {
      parent::__construct($id, $script, $fonction);
      $this->def_titre("Identifiant");
      $this->def_obligatoire();
    }
    
    protected function afficher_corps () {
      $this->afficher_ouverture_commune();
      echo 'type="text" maxlength="30" ';
      $affiche = ($this->valeur_definie())? 'value="' . $this->valeur() . '" ' : '';
      echo $affiche . ' />';
      if (isset($_GET['err']) && ($_GET['err'] == 'id'))
        echo "<p class=\"text-danger\">Erreur : identifiant inconnu</p>";
      if (isset($_GET['err']) && ($_GET['err'] == 'cnx'))
        echo "<p class=\"text-danger\">Erreur : connexion impossible avec cet identifiant</p>";
      if (isset($_GET['err']) && ($_GET['err'] == 'act'))
        echo "<p class=\"text-danger\">Connexion impossible : votre compte a été désactivé</p>";
    }
  }
  
  // --------------------------------------------------------------------------
  class Champ_Mot_Passe extends Champ_formulaire {
    public function __construct($id, $script = "", $fonction = "") {
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
      echo ' type="number" min="' . $this->valeur_min . '" max="' . $this->valeur_max . '" value="' . $this->valeur_min . '"/>';
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

  // ===========================================================================
?>
