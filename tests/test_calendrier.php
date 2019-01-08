<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : Fichier vide : modele entete
  // copyright (c) 2018 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php -f <thisfile>
  // dependances : 
  // teste avec : PHP 7.1 sur Mac OS 10.14
  // --------------------------------------------------------------------------
  // creation : 27-dec-2018 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // -
  // a faire :
  // -
  // ==========================================================================
  
  date_default_timezone_set("Europe/Paris");
  // indispensable car autrement les instants sont en UTC.
  
  require_once '../php/metier/calendrier.php';
  
  $cal = new calendrier();
  
  $aujourdhui = $cal->aujourdhui();
  echo $cal->date_texte($aujourdhui) . "\n";
  
  $maintenant = $cal->maintenant();
  echo $cal->heures_minutes_texte($maintenant) . "\n";
  
  $i1 = $cal->def_depuis_timestamp_sql("2018-10-08 20:05:47");
  echo $cal->date_texte($i1) . "\n"; // lundi 8 octobre 2018
  echo $cal->heures_minutes_texte($i1) . "\n";
  // ==========================================================================
?>
