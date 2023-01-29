<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : Definition de la classe Navigateur_Date
  //              permet de changer la date de reference pour la gestion des seances d'activite
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <nom_-fichier.php'
  // dependances : bootstrap 4.x
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 06-jul-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/element.php';
  require_once 'php/metier/calendrier.php';
  
  // --------------------------------------------------------------------------
  class Cadre_Controleur_Date extends Conteneur_Elements {
    
    private $format_colonnes = array();
    
    // TODO? : plutot redefinir ajouter_element pour masquer cette derniere
    public function ajouter_colonne(Element $element, string $format) {
      $this->elements[] = $element;
      $this->format_colonnes[] = $format;
    }
    
    protected function afficher_debut() {
      echo "\n<div class=\"container-fluid\" style=\"padding:0px;\">\n<div class=\"row\">\n";
    }
    
    protected function afficher_corps() {
      $i = 0;
      foreach ($this->elements as $element) {
        echo "<div class=\"col ", $this->format_colonnes[$i], "\">\n";
        $element->afficher();
        echo "</div>\n";
        $i += 1;
      }
    }
    
    protected function afficher_fin() {
      echo "\n</div></div>\n";
    }
  }
  
  
  // --------------------------------------------------------------------------
  abstract class Controleur_Date_Page extends Element {
    
    public $page_cible = "";
    public $date_ref; //  DateTimeInterface
    public $parametres = array(); // en plus du jour
    protected $code_html_parametres = "";
    private $jours = array();
    
    public function initialiser() {
      $this->definir_parametres_url();
      $this->definir_jours();
      return;
    }
    
    protected abstract function definir_jours();
    
    protected function definir_parametres_url() {
      $code_html = "";
      if (count($this->parametres) == 0) return;
      foreach ($this->parametres as $cle => $valeur) {
        $code_html = $code_html . "&" . $cle . "=" . $valeur;
      }
      $this->code_html_parametres = $code_html;
    }
    
  }
  
  class Navigateur_Date extends Controleur_Date_Page {
    private $jours = array();
    
    protected function definir_jours() {
      $date_ref = $this->date_ref;
      $this->jours[] = $date_ref->sub(new DateInterval('P7D'));
      $this->jours[] = $date_ref->sub(new DateInterval('P1D'));
      $this->jours[] = $date_ref;
      $this->jours[] = $date_ref->add(new DateInterval('P1D'));
      $this->jours[] = $date_ref->add(new DateInterval('P7D'));
    }
    
    protected function afficher_debut() {
      echo "\n<nav class=\"nav nav-pills flex-column flex-sm-row\">\n";
    }
    
    protected function afficher_corps() {
      foreach ($this->jours as $jour)
        $this->afficher_item($jour);
    }
    
    protected function afficher_item($date_jour) {
      $texte_jour = $date_jour->date_texte_court();
      $valeur_jour =  $date_jour->date_html(); //getTimestamp();
      $code_param_url = "?j=" . $valeur_jour . $this->code_html_parametres;
      echo "\n<a class=\"flex-sm-fill text-sm-center nav-link active\" href=\"", $this->page_cible, $code_param_url, "\">", $texte_jour, "</a>\n";
      
      /*
       echo "<a href=\"page_tableau_journalier_sorties.php?ta=" . $type_action . "&a=" . $action . "&d=" . $jour_ref . "&phd=" . $creneau_debut . "&phf=" . $creneau_fin . "&tb=" . $type_bateau . "\" class=\"btn btn-default btn-md\" role=\"button\"><span></span> "   . $texte_jour_ref . "</a>\n";
       */
    }
    
    protected function afficher_fin() {
      echo "</nav>\n";
    }
    
  }
  
  class Selecteur_Date extends Controleur_Date_Page {
    
    private $valeur_initiale;
    
    protected function definir_jours() {
      //$cal = Calendrier::obtenir();
      //$jour = new Instant($this->date_ref->getTimestamp());
      $this->valeur_initiale = $this->date_ref->date_html(); //$cal->date_html($jour);
    }
    
    protected function afficher_debut() {
      echo "\n<div>\n";
    }
    
    protected function afficher_corps() {
      // pour avoir un datepicker avec safari et ...
      echo "\n<script type=\"text/javascript\">";
       echo "$(function() {
       $( \"#j\" ).datepicker({
           altField: \"#j\",
           closeText: 'Fermer',
           prevText: 'Précédent',
           nextText: 'Suivant',
           currentText: 'Aujourd\'hui',
           firstDay: 1 ,
           monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
           monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
           dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
           dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
           dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
           weekHeader: 'Sem.',
           dateFormat: 'yy-mm-dd'
           });
       });";
       echo "</script>\n";
      echo "\n<script type=\"text/javascript\">";
      echo "$(function() { if ($('[type=\"date\"]').prop('type') != 'date' ) { $('[type=\"date\"]').datepicker(); }});\n";
      echo "</script>\n";
      
      echo "\n<form role=\"form\" name=\"", $this->id() , "\" method=\"get\" action=\"", $this->page_cible, "\">\n";
      
      foreach ($this->parametres as $cle => $valeur) {
        echo "<input type=\"hidden\" name=\"" . $cle . "\" value=\"", $valeur, "\">\n";
      }
      
      //echo "<input type=\"hidden\" id=\"j\" name=\"j\">"; // id pour javascript et name pour GET
      echo "<div class=\"form-row row\">\n";
      //echo "<label class=\"col-form-label col-sm-1\" for=\"date_sel\">Date</label>";
      //echo "<div class=\"col-md-10\">\n<input class=\"form-control\" type=\"date\" id=\"ds\" onchange=\" return convertir_date_timestamp('ds', 'j'); \" name=\"ds\" value=\"" . $this->valeur_initiale . "\" />";
      echo "<div class=\"col-md-10\">\n<input class=\"form-control\" type=\"date\" id=\"j\" name=\"j\" value=\"" . $this->valeur_initiale . "\" />";
      
      echo "</div>\n";
      echo "<div class=\"col-md-2\">";
      //echo '<div class="form-group form-btn col-sm-2" >'; //<div class="col-sm-offset-2 col-sm-10">';
      //echo '<input class="btn btn-sm btn-outline-primary" type="submit" id="' . $this->id() . '_valid"></div>';
      echo "<button type=\"submit\" class=\"btn btn-outline-primary\">Afficher</button>\n";
      echo "</div></div>\n</form>";
    }
    
    protected function afficher_fin() {
      echo "\n</div>\n";
    }
  }
  // ==========================================================================
?>
