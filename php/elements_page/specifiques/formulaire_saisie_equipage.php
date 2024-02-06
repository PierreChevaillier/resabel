<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 * description : Fichier vide -  modele entete
 * copyright (c) 2018-2024 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : php - require_once <chemin_vers_ce_fichier_php>
 * dependances :
 * - aucune
 * utilise avec :
 * - depuis 2023 :
 *   PHP 8.2 sur macOS 13.x
 *   PHP 8.1 sur hebergeur web
 * ----------------------------------------------------------------------------
 * creation : 30-jan-2024 pchevaillier@gmail.com
 * revision :
 * ----------------------------------------------------------------------------
 * commentaires :
 * - inception
 * attention :
 * -
 * a faire :
 * -
 * ============================================================================
 */

// --- Classes utilisees
require_once 'php/elements_page/generiques/element.php';

// ============================================================================
class Formulaire_Saisie_Equipage extends Element {

  public $seance;
  public $personnes_actives;
  public $personnes_occupees;
  
  public function __construct(Page $page,
                              //string $script_traitement,
                              //string $action,
                              string $id_objet) {
    $this->def_page($page);
    $this->def_id($id_objet);
  }
  
  public function initialiser() {
    // arguments des fonctions javascript
    $code_seance = $this->seance->code();
    $code_site = $this->seance->site->code();
    $code_support = $this->seance->code_support();
    $debut = $this->seance->debut()->date_heure_sql();
    $fin = $this->seance->fin()->date_heure_sql();
    $code_personne = 0;
    $inscr_resp = false;
    $code_action = 'i';
    $fonction = "requete_inscription_responsable(this"
      . ",'" . $code_seance . "'"
      . ",'" . $code_site . "'"
      . ",'" . $code_support . "'"
      . ",'" . $debut . "'"
      . ",'" . $fin . "')"
    ;
    /*
    echo "<p>" . $fonction . "</p>";
    try {
      // Saisie du chef de bord
      if ($this->seance->responsable_requis() && ! $this->seance->a_un_responsable()) {
        $id_champ = 'champ_resp';
        $champ_resp = new Champ_Selection($id_champ,
                                          'js/controle_inscription_equipage.js', $fonction);
        $this->ajouter_champ($champ_resp);
        $champ_resp->def_titre("Chef.fe de bord");
        $champ_resp->texte_aide = "Choisir un.e cheff.fe de bord dans la liste (optionnel)";
        $champ_resp->options[0] = 'aucun';
        foreach ($this->personnes_actives as $p) {
          if ($p->est_chef_de_bord() && !in_array($p->code(), $this->personnes_occupees))
            $champ_resp->options[$p->code()] = $p->prenom() . ' '. $p->nom();
        }
      }
      // autres membres de l'equipage
      $n_places_equipier = $this->seance->nombre_places_disponibles() - ($this->seance->a_un_responsable() ? 0 : 1);
      if ($n_places_equipier > 0) {
        foreach ($this->personnes_actives as $p) {
          $item = new Champ_Selection_Equipier($p->code());
          $item->texte = $p->prenom . ' ' . $p->nom;
          $est_participant = $this->seance->a_comme_participant($p);
          if (in_array($p->code(), $this->personnes_occupees) && !$est_participant)
            $item->desactive = true;
          if ($est_participant)
            $item->def_valeur(1);
          $this->ajouter_champ($item);
        }
      }
      parent::initialiser();
    } catch(Exception $e) {
      die('Exception dans la methode initialiser de la classe Formulaire_Connexion : ' . $e->getMessage());
    }
     */
  }
  
  protected function afficher_debut() {
    if ($this->a_un_titre())
      echo '<div class="well well-sm"><p class="lead">' . $this->titre() . '</p></div>' . PHP_EOL;
    $data = 'data-seance="' . $this->seance->code()
      . '" data-support="' . $this->seance->code_support()
      . '" data-debut="' . $this->seance->debut()->date_heure_sql()
      . '" data-npart="' . $this->seance->nombre_participants()
      . '"';
    echo '<form class="rsbl-form" role="form" ' . $data . ' id="' . $this->id() . '" name="' . $this->id() . '" onsubmit="return verification_formulaire(this)"  method="get" action="tutu.php">';
//    echo '<input type="hidden" name="a" value="' . $this->action . '" />';
  }
  
  protected function afficher_corps() {
    if ($this->seance->responsable_requis() && ! $this->seance->a_un_responsable())
      $this->afficher_champ_responsable();
    $n_places_equipier = $this->seance->nombre_places_disponibles() - ($this->seance->a_un_responsable() ? 0 : 1);
    if ($n_places_equipier > 0)
      $this->afficher_champs_saisie_equipage();
  }
  
  protected function afficher_fin() {
    echo PHP_EOL . '</form>' . PHP_EOL;
  }
  
  private function afficher_champ_responsable(): void {
    $id_champ = 'champ_resp';
    echo '<label class="form-label" for="' . $id_champ . '">Chef.fe de bord</label>';
    
    echo PHP_EOL . '<select class="form-control" ';
    echo ' id="' . $id_champ . '" name="' . $id_champ . '" ';
    echo 'onchange="controle_saisie_responsable(this);" ';
    echo '>' . PHP_EOL;
   
    
    echo '<option value="0" selected>Aucun.e</option>';
    foreach ($this->personnes_actives as $p) {
      if ($p->est_chef_de_bord() && !in_array($p->code(), $this->personnes_occupees))
        echo '<option value="' . $p->code() . '" >' . $p->prenom() . ' '. $p->nom() . '</option>' . PHP_EOL;
    }
    echo '</select>';
    echo '<div id="' . $id_champ . '_aide" class="form-text">Choisir un.e cheff.fe de bord dans la liste (optionnel)</div>';
  }
  
  private function afficher_champs_saisie_equipage():void {
    echo '<p>saisie équipage</p>';
    echo '<div class="rsbl-scroll">';
    foreach ($this->personnes_actives as $p) {
      $id = 'equip_' . $p->code();
      $texte = $p->prenom . ' ' . $p->nom;
      $desactive = in_array($p->code(), $this->personnes_occupees); // && !$est_participant)
      $valeur = 0;
      echo '<div class="form-check">';
      echo '<input type="checkbox" class="form-check-input" ';
      echo ' id="' . $id . '" name="' . $id . '" ';
      if ($this->seance->a_comme_participant($p)) {
        echo ' checked ';
        $valeur = 1;
      }
      echo ' value="' . $valeur . '" ';
      if ($desactive)
        echo ' disabled ';
      $params = 'this,' . $p->code() . ',"' . $texte . '"';
      $params = htmlspecialchars($params);
      echo ' onchange="controle_saisie_participation(' . $params . ');"/>';
        echo '<label class="form-check-label">' . $texte . '</label>' ;
      echo '</div>';
    }
    echo '</div>';
  }
  
}
// ============================================================================
?>
