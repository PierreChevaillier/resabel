<?php
// ========================================================================
// description : definition de la classe Element
// utilisation : classe racine des elements de pages web, pages comprises
// teste avec  : PHP 5.5.3 sur Mac OS 10.11
// contexte    : Elements generique d'un site web
// Copyright (c) 2017 AMP
// ------------------------------------------------------------------------
// creation : 04-juin-2017 pchevaillier@gmail.com
// revision :
// ------------------------------------------------------------------------
// commentaires :
// attention :
// a faire :
// ------------------------------------------------------------------------

// --- Classes utilisees

// ------------------------------------------------------------------------
// --- Definition de la classe Element

abstract class Element {

  /**
    * @var string
    */
  private $titre = "";
  public function titre() { return $this->titre; }
  public function def_titre($titre) { $this->titre = $titre; }
  
  /**
    *
    */
  public abstract function initialiser();

  /**
    *
    */
  final public function afficher() {
  	$this->afficher_debut();
    $this->afficher_corps();
    $this->afficher_fin();
  }

  /**
    *
    */
  protected abstract function afficher_debut();

  /**
    *
    */
  protected abstract function afficher_corps();

  /**
    *
    */
  protected abstract function afficher_fin();
}
// ========================================================================
