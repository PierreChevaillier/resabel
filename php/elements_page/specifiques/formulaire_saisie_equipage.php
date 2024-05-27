<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 * description : Fichier vide -  modele entete
 * copyright (c) 2018-2024 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : php - require_once <chemin_vers_ce_fichier_php>
 * dependances :
 * - bootstrap 5.x
 * - fonctions js executees suite actions sur formulaire
 *   (cf. controle_inscription_equipage.js)
 * utilise avec :
 * - depuis 2023 :
 *   PHP 8.2 sur macOS 13.x
 *   PHP 8.1 sur hebergeur web
 * ----------------------------------------------------------------------------
 * creation : 30-jan-2024 pchevaillier@gmail.com
 * revision : 19-fev-2024 pchevaillier@gmail.com
 * revision : 24-fev-2024 pchevaillier@gmail.com mise en page saisie equipage
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * -
 * a faire :
 * -
 * ============================================================================
 */

// --- Classes utilisees
require_once 'php/elements_page/generiques/element.php';
require_once 'php/elements_page/generiques/page.php';

require_once 'php/metier/seance_activite.php';

// ============================================================================
class Formulaire_Saisie_Equipage extends Element {

  public ?Seance_Activite $seance;
  public $personnes_actives;
  public $personnes_occupees;
  
  private $data = "";
  
  public function __construct(Page $page,
                              string $id_objet) {
    $this->def_page($page);
    $this->def_id($id_objet);
  }
  
  public function initialiser() {
    // donnees necessaires au controle de la saisie ou a son traitement
    $nb_resp_requis = 0;
    $nb_resp_inscrit = 0;
    if ($this->seance->responsable_requis()) {
      $nb_resp_requis = 1;
      if ($this->seance->a_un_responsable())
        $nb_resp_inscrit = 1;
    }
    $this->data = 'data-seance="' . $this->seance->code()
      . '" data-site="' . $this->seance->site->code()
      . '" data-support="' . $this->seance->code_support()
      . '" data-debut="' . $this->seance->debut()->date_heure_sql()
      . '" data-fin="' . $this->seance->fin()->date_heure_sql()
      . '" data-npart="' . $this->seance->nombre_participants()
      .'" data-resprequis="' . $nb_resp_requis
      .'" data-respinscrit="' . $nb_resp_inscrit
      . '"';
  }
  
  protected function afficher_debut() {
    if ($this->a_un_titre())
      echo '<div class="well well-sm"><p class="lead">' . $this->titre() . '</p></div>';
    echo '<form class="rsbl-form" role="form" ' . $this->data
      . ' id="' . $this->id()
      . '" name="' . $this->id()
      . '" onsubmit="return requete_inscription_groupe(this)" method="get">';
  }
  
  protected function afficher_corps() {
    $this->afficher_boutons_action();
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
    if ($this->seance->responsable_requis() && ! $this->seance->a_un_responsable())
      echo '<p>Autres membres de l\'équipage</p>';
    
    echo '<div class="rsbl-scroll">';
    echo '<div class="container">';
    echo '<div class="row row-cols-2 row-cols-sm-3 row-cols-md-6">';
    foreach ($this->personnes_actives as $p) {
      $id = 'equip_' . $p->code();
      $texte = $p->prenom . ' ' . $p->nom;
      $desactive = in_array($p->code(), $this->personnes_occupees); // && !$est_participant)
      $valeur = 0;
      echo ' <div class="col" style="padding:5px">';
      echo '<div class="form-check">';
      echo '<input type="checkbox" class="form-check-input" ';
      echo ' id="' . $id . '" name="participants" ';
      if ($this->seance->a_comme_participant($p)) {
        echo ' checked ';
        $valeur = 1;
      }
      $valeur = $p->code();
      echo ' value="' . $valeur . '" ';
      if ($desactive)
        echo ' disabled ';
      $params = 'this,' . $p->code() . ',"' . $texte . '",'
        . $p->est_chef_de_bord();
      $params = htmlspecialchars($params);
      echo ' onchange="controle_saisie_participation(' . $params . ');"/>';
        echo '<label class="form-check-label">' . $texte . '</label>' ;
      echo '</div>';
      echo '</div>';
    }
    echo '</div></div>';
    echo '</div>';
  }
  
  private function afficher_boutons_action(): void {
    echo '<div class="container" style="padding:10px"><div class="row">';
    echo '<div class="col-auto">';
    echo '<button class="btn btn-primary" type="submit" data-bs-toggle="modal" data-bs-target="#aff_act">Valider saisie</button>';
    echo '</div>';
    echo '<div class="col-auto">';
    echo '<button class="btn btn-secondary" type="reset" onclick="reinitialisation_saisie();">Ré-initialiser saisie</button>';
    echo '</div>';
    echo '</div></div>';
    return;
  }
}
// ============================================================================
?>
