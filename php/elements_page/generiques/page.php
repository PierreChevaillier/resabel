<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : definition des classes Page et Page_Simple
  // utilisation : structure commune a toutes les pages web du site
  // dependances : bootstrap 4.x, jquery, popper
  // teste avec  : PHP 7.1 sur Mac OS 10.14
  // contexte    : Elements generique d'un site web
  // copyright (c) 2017-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // creation: 04-jun-2017 pchevaillier@gmail.com
  // revision: 17-jun-2018 pchevaillier@gmail.com adaptation resabel V2
  // revision: 20-aug-2018 pchevaillier@gmail.com ajout feuilles de style
  // revision: 02-mar-2019 pchevaillier@gmail.com version 4.1.3 de bootsrap
  // --------------------------------------------------------------------------
  // commentaires :
  // - https://getbootstrap.com/docs/4.1/getting-started/introduction/
  // attention :
  // -
  // a faire :
  // -
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/element.php';
  require_once 'php/elements_page/generiques/pied_page.php';
  
  // --------------------------------------------------------------------------
  // --- Definition de la classe Page

  abstract class Page extends Element {
  
    public $javascripts = array();

    // --- Elements (code_html) dans la section <head> de la page
    private $elements_entete = array();
    public function ajoute_element_entete($code_html) {
      $this->elements_entete[] = $code_html;
     // $element->def_page($this);
    }

    // --- Elements en debut du <body>
    private $elements_haut = array();
    public function ajoute_element_haut($element) {
      $this->elements_haut[] = $element;
      $element->def_page($this);
    }

    protected $feuilles_style = array();
    
    private $contenus = array();
    public function ajoute_contenu($element) {
      $this->contenus[] = $element;
      $element->page = $this;
    }

    // --- Elements avant la fin du body
    private $elements_bas = array();
    public function ajoute_element_bas($element) {
      $this->elements_bas[] = $element;
      $element->page = $this;
    }
 
    public function definir_feuilles_style() {
      foreach ($this->feuilles_style as $f)
        echo "     <link rel=\"stylesheet\" href=\"" . get_include_path() . $f . "\" media=\"screen\" />\n";
    }
    
    public function __construct($nom_site, $nom_page, $liste_feuilles_style = null) {
      $this->def_titre($nom_site . " - " . $nom_page);
      if ($liste_feuilles_style != null)
        $this->feuilles_style = $liste_feuilles_style;
      $this->definir_elements();
    }
	
  public function initialiser() {
    //$this->definir_elements();
    foreach ($this->elements_haut as $e) $e->initialiser();
    foreach ($this->elements_bas as $e) $e->initialiser();
    foreach ($this->contenus as $e) $e->initialiser();
  }
  
  protected final function afficher_corps() {
  	echo "      <div class=\"container-fluid\" style=\"padding:2px;\">\n";
    foreach ($this->elements_haut as $e) $e->afficher();
    foreach ($this->contenus as $e) $e->afficher();
    foreach ($this->elements_bas as $e) $e->afficher();
    echo "\n      </div>\n";
  }

  protected function afficher_titre() {
     echo "      <title>" . $this->titre() . "</title>\n";
  }
  
  abstract protected function inclure_meta_donnees_open_graph(); // pour Facebook
  
  protected function afficher_debut() {
    echo "<head>\n      <meta charset=\"utf-8\" />";
    
    $this->inclure_meta_donnees_open_graph();
    
    echo "  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" />
      <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\" />\n";
    
    // Bootstrap CSS
    echo "      <link href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css\" rel=\"stylesheet\" integrity=\"sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO\" crossorigin=\"anonymous\" />\n";
    
    // Feuille de style locale (screen)
    foreach ($this->feuilles_style as $css)
      echo "      <link rel=\"stylesheet\" href=\"" . get_include_path() . "/" . $css . "\" media=\"screen\" />\n";
    
    // Jquery
    echo "      <script src=\"https://code.jquery.com/jquery-3.3.1.slim.min.js\" integrity=\"sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo\" crossorigin=\"anonymous\"></script>\n";
  
    //Popper
    echo "      <script src=\"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js\" integrity=\"sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49\" crossorigin=\"anonymous\"></script>\n";
    
    // Bootstrap javascript
    echo "      <script src=\"https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js\" integrity=\"sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy\" crossorigin=\"anonymous\"></script>\n";
    
    foreach ($this->javascripts as $scripts) {
      echo "      <script src=\"" . get_include_path() . $scripts . "\"></script>\n";
    }
    foreach ($this->elements_entete as $e)
      echo $e;
    $this->afficher_titre();
    echo "    </head>\n    <body>\n";
  }

  protected function afficher_fin() {
  	echo "      </body>\n";
  }

  abstract protected function definir_elements();

}

class Page_Simple extends Page {
  public function __construct($nom_site, $nom_page, $liste_feuilles_style = null) {
    parent::__construct($nom_site, $nom_page, $liste_feuilles_style);
    $pp = new Pied_Page();
    $pp->def_titre("Resabel - V2");
    $this->ajoute_element_bas($pp);
  }
    
  protected function inclure_meta_donnees_open_graph() {
    // de base : aucune
  }
    
  protected function definir_elements() {
    // de base : rien
 
  }
}
  
// ========================================================================
