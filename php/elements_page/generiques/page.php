<?php
  // ==========================================================================
  // description : definition des classes Page et Page_Simple
  // utilisation : structure commune a toutes les pages web du site
  // dependances : boostrap
  //               jquery
  // teste avec  : PHP 5.5.3 sur Mac OS 10.11
  // contexte    : Elements generique d'un site web
  // copyright (c) 2017-2018 AMP. All rights reserved.
  // --------------------------------------------------------------------------
  // creation: 04-jun-2017 pchevaillier@gmail.com
  // revision: 17-jun-2018 pchevaillier@gmail.com adaptation resabel V2
  // revision: 20-aug-2018 pchevaillier@gmail.com ajout feuilles de style
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // - pas si generique que ca : feuille de style
  // a faire :
  // -
  // ==========================================================================

  // --- Classes utilisees
  require_once 'element.php';

  // --------------------------------------------------------------------------
  // --- Definition de la classe Page

  abstract class Page extends Element {
  
    public $javascripts = array();

    // --- Elements dans la section <head> de la page
    private $elements_entete = array();
    public function ajoute_element_entete($element) {
      $this->elements_entete[] = $element;
      $element->page = $this;
    }

    // --- Elements en debut du <body>
    private $elements_haut = array();
    public function ajoute_element_haut($element) {
      $this->elements_haut[] = $element;
      $element->page = $this;
    }

    
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
    
    public function __construct($nom_site, $nom_page, $feuilles_style) {
      $this->def_titre($nom_site . " - " . $nom_page);
      $this->feuilles_style = $feuilles_style;
    }
	
  public function initialiser() {
    $this->definir_elements();
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
    echo "      <link href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css\" rel=\"stylesheet\" integrity=\"sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB\" crossorigin=\"anonymous\" />\n";
    
    // Feuille de style locale
   // echo "     <link rel=\"stylesheet\" href=\"" . get_include_path() . "amp_france2018.css\" media=\"screen\" />\n";
    
    // Jquery
    echo "      <script src=\"https://code.jquery.com/jquery-3.3.1.js\" integrity=\"sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=\" crossorigin=\"anonymous\"></script>\n";
    
    // Bootstrap javascript
    echo "      <script src=\"https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js\" integrity=\"sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T\" crossorigin=\"anonymous\"></script>\n";
    
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
    protected function inclure_meta_donnees_open_graph() {
        //de base : aucune
    }
    
    protected function definir_elements() {
      //de base : rien
    }
  }
  
// ========================================================================
