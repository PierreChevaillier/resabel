<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : Definition des classes pour le controle du changement
  //               de la date de reference pour la gestion des seances d'activite
  // copyright (c) 2018-2024 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <nom_-fichier.php'
  // dependances : bootstrap 5.3
  // utilise avec :
  //  - PHP 8.2 sur macOS 13.2
  // --------------------------------------------------------------------------
  // creation : 06-jul-2019 pchevaillier@gmail.com
  // revision : 21-mar-2023 pchevaillier@gmail.com bootstrap 5.2 + Menu_Navigation_Date
// revision : 19-fev-2024 pchevaillier@gmail.com correction definir_parametres_url()
// revision : 15-jul-2024 pchevaillier@gmail.com * nav date
  // --------------------------------------------------------------------------
  // commentaires :
  // - ergonomie de Menu_navigation_Date sans doute meilleure que Selecteur_Date
  // attention :
  // a faire :
  // - changer couleur bouton validation en fonction du theme
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
      echo '<div class="container-fluid" style="padding:0px;"><div class="row">';
    }
    
    protected function afficher_corps() {
      $i = 0;
      foreach ($this->elements as $element) {
        echo '<div class="col ', $this->format_colonnes[$i], '">';
        $element->afficher();
        echo '</div>';
        $i += 1;
      }
    }
    
    protected function afficher_fin() {
      echo '</div></div>';
    }
  }
  
  
  // --------------------------------------------------------------------------
  abstract class Controleur_Date_Page extends Element {

    public DateTimeInterface $date_ref;
    public string $page_cible = "";
    public $parametres = array(); // en plus du jour (j)
    protected string $code_html_parametres = "";
    private $jours = array();
    
    public function initialiser(): void {
      $this->definir_parametres_url();
      $this->definir_jours();
      return;
    }
    
    protected abstract function definir_jours(): void;
    
    protected function definir_parametres_url(): void {
      if (count($this->parametres) == 0) return;
      $code_html = "";
      foreach ($this->parametres as $cle => $valeur) {
        $code_html = $code_html . "&" . $cle . "=" . $valeur;
      }
      $this->code_html_parametres = $code_html;
    }
    
  }
  
// ----------------------------------------------------------------------------
  class Navigateur_Date extends Controleur_Date_Page {
    private $jours = array();
    
    protected function definir_jours(): void {
      $date_ref = $this->date_ref;
      $this->jours[] = $date_ref->sub(new DateInterval('P7D'));
      $this->jours[] = $date_ref->sub(new DateInterval('P1D'));
      $this->jours[] = $date_ref;
      $this->jours[] = $date_ref->add(new DateInterval('P1D'));
      $this->jours[] = $date_ref->add(new DateInterval('P7D'));
    }
    
    protected function afficher_debut() {
      echo '<nav class="nav nav-pills flex-column flex-sm-row">';
    }
    
    protected function afficher_corps() {
      foreach ($this->jours as $jour)
        $this->afficher_item($jour);
    }
    
    protected function afficher_item($date_jour): void {
      $texte_jour = $date_jour->date_texte_court();
      $valeur_jour =  $date_jour->date_html();
      $code_param_url = "?j=" . $valeur_jour . $this->code_html_parametres;
      echo '<a class="flex-sm-fill text-sm-center nav-link active" href="', $this->page_cible, $code_param_url, '">', $texte_jour, "</a>";
      return;
    }
    
    protected function afficher_fin() {
      echo '</nav>';
    }
    
  }

// ----------------------------------------------------------------------------
  class Selecteur_Date extends Controleur_Date_Page {
    
    private $valeur_initiale;
    
    protected function definir_jours(): void {
      $this->valeur_initiale = $this->date_ref->date_html();
    }
    
    protected function afficher_debut() {
      echo '<div>';
    }
    
    protected function afficher_corps() {
      // pour avoir un datepicker avec safari et ...
      /*
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
      */
      echo "<form role=\"form\" name=\"", $this->id() , "\" method=\"get\" action=\"", $this->page_cible, "\">\n";
      
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
      echo "<button type=\"submit\" class=\"btn btn-outline-primary\">Afficher</button>";
      echo "</div></div></form>";
    }
    
    protected function afficher_fin() {
      echo '</div>';
    }
  }

// ----------------------------------------------------------------------------
class Menu_Navigation_Date extends Controleur_Date_Page {
  private $jours = array();
  
  protected function definir_jours(): void {
    $this->jours[] = $this->date_ref->sub(new DateInterval('P7D'));
    $this->jours[] = $this->date_ref->sub(new DateInterval('P1D'));
    $this->jours[] = $this->date_ref;
    $this->jours[] = $this->date_ref->add(new DateInterval('P1D'));
    $this->jours[] = $this->date_ref->add(new DateInterval('P7D'));
  }
  
  protected function afficher_debut() {
    echo '<nav class="navbar navbar-expand-md bg-body-tertiary" id ="' . $this->id() . '">';
    echo '<div class="container-fluid">';
    $txt = ($this->titre() == "") ? "Date ": $this->titre();
    echo '<span class="navbar-brand mb-0 h4">' . $txt . '</span>';//echo '<a class="navbar-text">' . $txt . '</a>';
    $content_id = $this->id() . '_navbar-cont';
   
    echo '<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#' . $content_id
      . '" aria-controls="' . $content_id
      . '" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>';
    echo '<div class="collapse navbar-collapse" id="' . $content_id . '">';
   
  }
  
  protected function afficher_corps() {
   
    $valeur_jour = $this->date_ref->date_html();
    $code_param_url = $this->code_html_parametres;
    $code_action = $this->page_cible . '?x=0' . $code_param_url;
    echo '<form class="d-flex" action="' . $code_action . '" method="GET">';
    echo '<input class="form-control me-2" id="j" name="j" type="date" value="' . $valeur_jour
      . '" aria-label="Date">';
    foreach ($this->parametres as $cle => $valeur) {
      echo '<input type="hidden" id="' . $cle . '" name="' . $cle .'" value="' . $valeur . '">';
    }
    echo '<button class="btn btn-outline-primary" type="submit">Afficher</button>';
    echo'</form>';
    echo '<ul class="navbar-nav me-auto mb-2 mb-lg-0">';
    $nb_date = count($this->jours);
    $i = 0;
    foreach ($this->jours as $jour) {
      if ($jour < $this->date_ref) {
        if ($i == 0)
          $texte_jour = '<< ' . $jour->date_texte_court();
        else
          $texte_jour = '< ' . $jour->date_texte_court();
      } elseif ($jour == $this->date_ref) {
        $texte_jour = $jour->date_texte_court();
      } else {
        if ($i == ($nb_date - 1))
          $texte_jour = $jour->date_texte_court() . ' >>';
        else
          $texte_jour = $jour->date_texte_court() . ' >';
      }
      $valeur_jour = $jour->date_html();
      $code_param_url = "?j=" . $valeur_jour . $this->code_html_parametres;
      echo '<a class="nav-link" href="' . $this->page_cible . $code_param_url . '">'
        . '<span>' .$texte_jour . '</span></a>';
      $i = $i + 1;
    }
    echo '</ul>';
  }
    
  protected function afficher_fin() {
    echo '</div></div></nav>';
  }
}
// ============================================================================
?>
