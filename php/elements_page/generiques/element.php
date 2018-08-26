<?php
  // ========================================================================
  // description : definition de la classe Element et des classes derivees
  //               correspondant aux elements generiques
  // utilisation : classes racines des elements de pages web, pages comprises
  // teste avec  : PHP 5.5.3 sur Mac OS 10.11
  // contexte    : Elements generique d'un site web
  // Copyright (c) 2017-208 AMP. Tous droits reserves.
  // ------------------------------------------------------------------------
  // creation : 04-jun-2017 pchevaillier@gmail.com
  // revision : 06-fev-2018 pchevaillier@gmail.com ajout Conteneur_Elements
  // revision : 17-jun-2018 pchevaillier@gmail.com ajout classe Element_Code
  // ------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // ------------------------------------------------------------------------

  // --- Classes utilisees

  // --------------------------------------------------------------------------
  abstract class Element {
    
    protected $page = null;
    public function def_page($page_web) {
      $this->page = $page_web;
    }
    
    private $titre = "";
    public function titre() { return $this->titre; }
    public function def_titre($titre) { $this->titre = $titre; }
    public function a_un_titre() { return strlen($this->titre) > 0; }
  
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
    private $code = "";
    public function code() { return $this->titre; }
    public function def_code($code_html) { $this->code = $code_html; }
    public function est_non_vide() { return strlen($this->code) > 0; }
    
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
    
    public function initialiser() {
      foreach ($this->elements as $element)
        $element->initialiser();
    }
    
    protected function afficher_debut() {
      echo "\n";
    }
    
    protected function afficher_corps() {
      foreach ($this->elements as $element)
        $element->afficher();
    }
    
    protected function afficher_fin() {
      echo "\n";
    }
  }
  
  // ========================================================================
