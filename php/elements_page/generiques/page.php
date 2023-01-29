<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : definition des classes Page et Page_Simple
  // utilisation : structure commune a toutes les pages web du site
  // dependances : bootstrap 4.x, jquery, popper
  // teste avec  : PHP 7.1 sur Mac OS 10.14
  // contexte    : Elements generique d'un site web
  // copyright (c) 2017-2020 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // creation: 04-jun-2017 pchevaillier@gmail.com
  // revision: 17-jun-2018 pchevaillier@gmail.com adaptation resabel V2
  // revision: 20-aug-2018 pchevaillier@gmail.com ajout feuilles de style
  // revision: 02-mar-2019 pchevaillier@gmail.com version 4.1.3 de bootstrap
  // revision: 05-avr-2019 pchevaillier@gmail.com version 4.3.1 de bootstrap
  // revision: 11-mai-2019 pchevaillier@gmail.com jquery UI
  // revision: 28-mar-2020 pchevaillier@gmail.com script / activation tooltips Bootstrap
  // revision: 13-avr-2020 pchevaillier@gmail.com ajouter_script pour eviter doublons
  // --------------------------------------------------------------------------
  // commentaires :
  // - https://getbootstrap.com/docs/4.1/getting-started/introduction/
  // attention :
  // -
  // a faire :
  // - ajouter favicon : <link rel="icon" href="data:;base64,iVBORw0KGgo=">
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/element.php';
  require_once 'php/elements_page/generiques/pied_page.php';
  
  // --------------------------------------------------------------------------
  // --- Definition de la classe Page

  abstract class Page extends Element {
  
    public $javascripts = array();
    public function ajouter_script(String $chemin_fichier) {
      $existe = false;
      if ($chemin_fichier == "")
        return false;
      foreach ($this->javascripts as $s) {
        if (strcmp($s, $chemin_fichier) == 0) {
          $existe = true;
          break;
        }
      }
      if (!$existe) {
        $this->javascripts[] = $chemin_fichier;
      }
      return !$existe; // <=> ajout effectue
    }
    
    public $prive = true;
    
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
    // Le conteneur des elements de la page a comme id celui de la page
    // ceci permet de l'identifier comme le parent d'elements du document HTML
    // qui seraient crees dynamiquement par un script
    $html_id = (strlen($this->id()) > 0) ? " id=\"" . $this->id() . "\" " : " ";
    echo "      <div class=\"container-fluid\"" . $html_id . " style=\"padding:2px;\">\n";
    
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
    
    if ($this->prive)
      echo "\n      <meta name=\"robots\" content=\"none\" />";
    else
      $this->inclure_meta_donnees_open_graph();
    
    echo "\n      <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" />
      <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\" />\n";
    
    // Bootstrap CSS
    echo "      <link href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css\" rel=\"stylesheet\" integrity=\"sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T\" crossorigin=\"anonymous\" />\n";
    
    echo "      <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css\" />\n";
    
    // Feuille de style locale (screen)
    foreach ($this->feuilles_style as $css)
      echo "      <link rel=\"stylesheet\" href=\"" . get_include_path() . "/" . $css . "\" media=\"screen\" />\n";
    
    // Jquery
    echo "      <script src=\"https://code.jquery.com/jquery-3.3.1.min.js\" integrity=\"sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=\" crossorigin=\"anonymous\"></script>\n";
    /*
     // semble incompatible avec jquery-3.3.1.min.js
    echo "      <script src=\"https://code.jquery.com/jquery-3.3.1.slim.min.js\" integrity=\"sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo\" crossorigin=\"anonymous\"></script>\n";
  */
    // Popper (requis pour bootstrap modal)
    echo "      <script src=\"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js\" integrity=\"sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1\" crossorigin=\"anonymous\"></script>\n";
    
    // Bootstrap javascript
    echo "      <script src=\"https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js\" integrity=\"sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM\" crossorigin=\"anonymous\"></script>\n";
    
    // JQuery UI (pour datepicker ...)
    echo "      <script src=\"https://code.jquery.com/ui/1.12.1/jquery-ui.min.js\" integrity=\"sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=\" crossorigin=\"anonymous\"></script>";
    
    foreach ($this->javascripts as $scripts) {
      echo "      <script src=\"" . get_include_path() . $scripts . "\"></script>\n";
    }
    foreach ($this->elements_entete as $e)
      echo $e;
    $this->afficher_titre();
    
    // Activation des tooltips Bootstrap pour les elements de la classe rsbl-tooltip
    echo "      <script> $(function () { $('.rsbl-tooltip').tooltip() })</script>" . PHP_EOL;
    
    echo "      <script> $(function () { $('[data-toggle=\"popover\"]').popover(); var myDefaultWhiteList = $.fn.popover.Constructor.Default.whiteList; myDefaultWhiteList.div = ['class']; myDefaultWhiteList.a = ['target', 'href', 'title', 'rel', 'class']; })</script>" . PHP_EOL;
    echo "    </head>\n    <body>\n";
  }

  protected function afficher_fin() {
  	echo "    </body>\n";
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
