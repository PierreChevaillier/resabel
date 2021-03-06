<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : test de traitement d'une requete AJAX
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : requete Ajax
  // dependances : essai_ajax.html
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 17-mar-2019 pchevaillier@gmail.com
  // revision : 01-avr-2019 pchevaillier@gmail.com JSON
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // -
  // a faire :
  // - test avec json :
  // json_encode ( mixed $value [, int $options = 0 [, int $depth = 512 ]] ) : string
  // echo(json_encode($tuple_requete_sql)
  // ==========================================================================

  if (isset($_GET['fmt']) && $_GET['fmt'] == 'json') {
    // on recoit :
    $code = $_GET['code'];
    $niveau = $_GET['niveau'];
    
    // on retourne :
    $donnee = array('code' => $code, 'niveau' => $niveau);
    $resultat_json = json_encode($donnee);
    echo $resultat_json;
    
  } else {
    
    $code = $_GET['mbr'];
    $valeur = $_GET['v'];
    echo $code . ':' . $valeur;
  }
  
  // ==========================================================================
?>
