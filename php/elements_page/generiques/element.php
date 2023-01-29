<?php
  // =========================================================================
  // contexte    : Elements generiques d'un site web
  // description : definition des classes Element et Conteneurs_element (composite)
  //               correspondant aux elements generiques d'une page web
  // utilisation : php - require_once <chemin_vers_ce_fichier_php>
  // dependances : classe Page
  // utilise avec :
  //   >= 2023: PHP 7.1 sur macOS 10.14 ; PHP 7.3 sur macOS 13.2
  // Copyright (c) 2017-2023 AMP. Tous droits reserves.
  // -------------------------------------------------------------------------
  // creation : 04-jun-2017 pchevaillier@gmail.com
  // revision : 06-fev-2018 pchevaillier@gmail.com ajout Conteneur_Elements
  // revision : 17-jun-2018 pchevaillier@gmail.com ajout classe Element_Code
  // revision : 28-jan-2023 pchevaillier@gmail.com typage, correction suite TU
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // -
  // a faire :
  // -
  // ==========================================================================

  // --- Classes utilisees

  // --------------------------------------------------------------------------
  abstract class Element {
    
    protected ?Page $page = null;
    final public function def_page(Page $page_web) {
      $this->page = $page_web;
    }
    final public function page() { return $this->page; }
    
    private string $titre = "";
    final public function titre(): string { return $this->titre; }
    final public function def_titre(string $titre): void { $this->titre = $titre; }
    final public function a_un_titre(): bool { return strlen($this->titre) > 0; }
  
    protected string $id = ''; // change le 25-01-2023 (avant : id_objet)
    final public function def_id(string $valeur): void { $this->id = $valeur; }
    final public function id(): string { return $this->id; }
    
    public abstract function initialiser();

    final public function afficher() {
      $this->afficher_debut();
      $this->afficher_corps();
      $this->afficher_fin();
    }

    protected abstract function afficher_debut();
    
    protected abstract function afficher_corps();
    
    protected abstract function afficher_fin();
  }
  
  // --------------------------------------------------------------------------
  class Element_Code extends Element {
    private string $code = "";
    public function code(): string { return $this->code; }
    public function def_code(string $code_html): void { $this->code = $code_html; }
    public function est_non_vide(): bool { return strlen($this->code) > 0; }
    
    public function initialiser() {
    }
    
    protected function afficher_debut() {
    }
    
    protected function afficher_corps() {
      echo $this->code;
    }
    
    protected function afficher_fin() {
    }
  }
  
  // --------------------------------------------------------------------------
  class Conteneur_Elements extends Element {
    public $elements = array();
    
    public function ajouter_element(Element $elem) {
      $this->elements[] = $elem;
    }
    
    public function initialiser() {
      foreach ($this->elements as $element)
        $element->initialiser();
    }
    
    protected function afficher_debut() {
      echo PHP_EOL;
    }
    
    protected function afficher_corps() {
      foreach ($this->elements as $element)
        $element->afficher();
    }
    
    protected function afficher_fin() {
      echo PHP_EOL;
    }
  }
  
  // =========================================================================
?>
