<?php
/* ============================================================================
 * Resabel - systeme de REServAtion de Bateau En Ligne
 * Copyright (C) 2024 Pierre Chevaillier
 * contact: pchevaillier@gmail.com 70 allee de Broceliande, 29200 Brest, France
 * ----------------------------------------------------------------------------
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License,
 * or any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 * ----------------------------------------------------------------------------
 * description : Definition de la classe Formulaire_Indisponibilite
 *               Formulaire de saisie/modification des informations relatives
 *               a une fermeture de site ou indisponibilite d'un support d'activite
 * utilisation : php - require_once <chemin_vers_ce_fichier_php>
 * dependances :
 * ----------------------------------------------------------------------------
 * creation : 13-mai-2024 pchevaillier@gmail.com
 * revision : 07-oct-2024 pchevaillier@gmail.com * valeurs initiales heures debut et fin (issue #25)
 * revision :
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * -
 * a faire :
 * - controle des valeurs des intants de début et fin
 * ============================================================================
 */

// --- Classes utilisees
require_once 'php/metier/indisponibilite.php';

require_once 'php/elements_page/generiques/formulaire.php';
require_once 'php/elements_page/generiques/champ_formulaire.php';
require_once 'php/elements_page/generiques/page.php';

require_once 'php/bdd/enregistrement_indisponibilite.php';
require_once 'php/bdd/enregistrement_site_activite.php';
require_once 'php/bdd/enregistrement_support_activite.php';
require_once 'php/bdd/enregistrement_motif_indisponibilite.php';
// ============================================================================
class Formulaire_Indisponibilite extends Formulaire {
  
  private ?Indisponibilite $indisponibilite = null;
  
  public function __construct(Page $page,
                              Indisponibilite $indisponibilite) {
    
    $this->indisponibilite = $indisponibilite;
    $this->message_bouton_validation = "Valider";
    $this->confirmation_requise = true;
    
    // Parametrage de l'appel du script php qui traite
    // les donnees saisies ou modifiees grace au formulaire
    $script_traitement = 'php/scripts/indisponibilite_maj.php?';
    $action = $_GET['act'];
    $params = 'act=' . $action . '&typ=' . $_GET['typ'];
    //if (isset($action) && $action == 'm')
      $params = $params . (isset($this->indisponibilite) ? '&id=' . $this->indisponibilite->code() : '');
    $script_traitement = $script_traitement . $params;
      
    $id = 'form_indisp';
    parent::__construct($page, $script_traitement, $action, $id);
    
  }
  
  public function initialiser() {
    
    $item = null;
    /*
    if (isset($_GET['a']) && $_GET['a'] == 'c') {
      $code_chargement = new Element_Code();
      $script = "\n<script>window.onload = function() {creer_gestionnaire_evenement('id', 'prn', 'nom'); };</script>\n";
      $code_chargement->def_code($script);
      $this->page->ajoute_contenu($code_chargement);
    }
    */
    try {
      $code_type = 0;
      if (is_a($this->indisponibilite, 'Fermeture_Site')) {
        $code_type = Enregistrement_Indisponibilite::CODE_TYPE_INDISPO_SITE;
        
        $item = new Champ_Selection("site");
        $item->def_titre("Site fermé");
        $item->def_obligatoire();
        $item->valeurs_multiples = False;
        $sites = array();
        Enregistrement_Site_Activite::collecter("","", $sites);
        foreach ($sites as $code => $site)
          $item->options[$code] = $site->nom();
        $this->ajouter_champ($item);
      }
      
      if (is_a($this->indisponibilite, 'Indisponibilite_Support')) {
        $code_type = Enregistrement_Indisponibilite::CODE_TYPE_INDISPO_SUPPORT;
        $item = new Champ_Selection("support");
        $item->def_titre("Support(s) indisponible(s)");
        $item->def_obligatoire();
        $item->valeurs_multiples = true;
        $supports = array();
        Enregistrement_Support_Activite::collecter("","", $supports);
        foreach ($supports as $code => $support)
          $item->options[$code] = $support->numero() . ' ' . $support->nom();
        $this->ajouter_champ($item);
      }
      
      $item = new Champ_Date("date_deb", "", "");
      $item->def_titre("Date de début");
      $item->def_obligatoire();
      $this->ajouter_champ($item);

      $item = new Champ_Heure("hre_deb", "", "");
      $item->def_titre("Heure de début");
      $item->def_obligatoire();
      $this->ajouter_champ($item);
      
      $item = new Champ_Date("date_fin", "", "");
      $item->def_titre("Date de fin");
      $item->def_obligatoire();
      $this->ajouter_champ($item);

      $item = new Champ_Heure("hre_fin", "", "");
      $item->def_titre("Heure de fin");
      $item->def_obligatoire();
      $this->ajouter_champ($item);
      
      $item = new Champ_Selection("motif");
      $item->def_titre("Motif");
      $item->def_obligatoire();
      $item->valeurs_multiples = False;
      $motifs = array();
      Enregistrement_Motif_Indisponibilite::collecter($code_type, $motifs);
      foreach ($motifs as $code => $motif)
        $item->options[$code] = $motif->nom();
      $this->ajouter_champ($item);
      
      $item = new Champ_Texte("info", "", "");
      $item->def_titre("Explication");
      $this->ajouter_champ($item);
      
      parent::initialiser();
    } catch(Exception $e) {
      die('Exception dans la methode initialiser de la classe Formulaire_Support_Activite : ' . $e->getMessage());
    }
  }
  
  public function initialiser_champs() {
    $ok = isset($this->indisponibilite);
    $action = $_GET['act'];
    if ($ok) {
      if ($action == 'm') {
        if (is_a($this->indisponibilite, 'Fermeture_Site')) {
          $this->champ('site')->def_valeur($this->indisponibilite->code_objet());
        } elseif (is_a($this->indisponibilite, 'Indisponibilite_Support')) {
          $this->champ('support')->def_valeur($this->indisponibilite->code_objet());
        }
        $this->champ('date_deb')->def_valeur($this->indisponibilite->debut()->date_html());
        $this->champ('hre_deb')->def_valeur($this->indisponibilite->debut()->format('H:i'));
        $this->champ('date_fin')->def_valeur($this->indisponibilite->fin()->date_html());
        $this->champ('hre_fin')->def_valeur($this->indisponibilite->fin()->format('H:i'));
        $this->champ('motif')->def_valeur($this->indisponibilite->motif()->code());
        $this->champ('info')->def_valeur($this->indisponibilite->information());
      } elseif ($action == 'c') {
        $aujourdhui = Calendrier::aujourdhui();
        $debut = $aujourdhui->add(new DateInterval('PT1H0M0S'));
        $demain = $aujourdhui->lendemain();
        $fin = $demain->add(new DateInterval('PT23H0M0S'));
        $this->champ('date_deb')->def_valeur($debut->date_html());
        $this->champ('hre_deb')->def_valeur($debut->format('H:i'));
        $this->champ('date_fin')->def_valeur($fin->date_html());
        $this->champ('hre_fin')->def_valeur($fin->format('H:i'));
      }
    }
    return $ok;
  }
}
// ============================================================================
?>
