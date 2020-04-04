<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : affichage d'un conteneur repliable  (collapsible) d'elements
  // copyright (c) 2018-2020 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : bootstrap 4.-x (teste avec 4.3)
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 08-jul-2019 pchevaillier@gmail.com
  // revision : 04-apr-2020 pchevaillier@gmail.com card et changement symbole (js)
  // --------------------------------------------------------------------------
  // commentaires :
  // - https://getbootstrap.com/docs/4.3/components/collapse/
  // attention :
  // a faire :
  // ==========================================================================

  // --------------------------------------------------------------------------
  class Conteneur_Repliable extends Conteneur_Elements {
    
    private $symbole_deplier = '<img src="../../assets/icons/arrows-expand.svg" alt="+" width="24" height="24" title="Démasquer contenu">';
    private $symbole_plier = '<img src="../../assets/icons/arrows-collapse.svg" alt="-" width="24" height="24" title="Masquer contenu">';
 
    /*
    protected function afficher_debut() {
      $code_bouton = '<img src="../../assets/icons/arrow-up-down.svg" alt="+/-" width="24" height="24" class="rsbl-tooltip" title="Masquer/Démasquer contenu">';
      
      echo '<div class="accordion" id="accordion_', $this->id(), '"><div class="card"><div class="card-header" id="heading_', $this->id(), '">';
      //  class="mb-0"
      echo '<h5>' . $this->titre() . ' <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse_' . $this->id(), '" aria-expanded="false" aria-controls="collapse_', $this->id(), '">' . $code_bouton, '</button></h5></div>';
      echo '<div id="collapse_', $this->id(), '" class="collapse" aria-labelledby="heading_', $this->id(), '" data-parent="#accordion_', $this->id(),'"><div class="card-body">';
    }
     */
 
    protected function afficher_debut() {
      // Conteneur
      echo '<div class="card" id="card_', $this->id(), '"><div class="card-body" id="card_body_',  $this->id(),  '">';
      
      echo '<h5 class="card-title">', $this->titre(), '<button id="btn_ctrl_', $this->id(), '" class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse_', $this->id(),  '" aria-expanded="false" aria-controls="collapse_',  $this->id(),  '">', $this->symbole_deplier, '</button></h5>';
      
      // Zone repliable (collapsible)
      echo '<div id="collapse_', $this->id(), '" class="collapse" aria-labelledby="heading_', $this->id(), '" data-parent="#card_body_', $this->id(),'">';
      // echo '<div class="card"><div class="card-body">'; // contenu de la zone repliable
  
    }
        
    protected function afficher_fin() {
      echo '</div></div>', PHP_EOL; // conteneur
      echo '</div>', PHP_EOL; // contenu repliable
      
      // Script pour changement symbole du bouton controllant le pliage/depliage du contenu
      $html_plier = addslashes($this->symbole_plier);
      $html_deplier = addslashes($this->symbole_deplier);
      echo "<script> $('#collapse_" . $this->id() . "').on('shown.bs.collapse', function(){ document.getElementById('btn_ctrl_" . $this->id() . "').innerHTML = \"" . $html_plier . "\"; }).on('hidden.bs.collapse', function(){ document.getElementById('btn_ctrl_" . $this->id() . "').innerHTML = \"" . $html_deplier . "\"; }); </script>" . PHP_EOL;
      
      //echo '</div></div>' . PHP_EOL; // contenu de la zone repliable
    }
  }
  
  // ==========================================================================
?>
