<?php
  // ---------------------------------------------------------------------------
  // description: Fonction de formatage des donnees stockees en base de donnees
  // contexte   : resabel
  // Copyright (c) 2014-2017 AMP. All rights reserved
  // ---------------------------------------------------------------------------
  // creation: 29-aug-2016 pchevaillier@gmail.com
  // revision:
  // ---------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // ---------------------------------------------------------------------------

function formatter_num_tel_affichage($numero) {
	$tel = array();
	$bon_separateur = " "; 
	$mauvais_separateurs = array(".", "-");
	$resultat = "";
  if (strlen($numero) === 0) {
  	for ($i = 0; $i < 5; $i++)
    	$tel[$i] = " ";
  } elseif (strlen($numero) === 10) {
    $tel[0] = substr($numero, -10, 2);
    $tel[1] = substr($numero, -8, 2);
    $tel[2] = substr($numero, -6, 2);
    $tel[3] = substr($numero, -4, 2);
    $tel[4] = substr($numero, -2, 2);
  }	elseif (strlen($numero) === 14) {
  	$x = str_replace($mauvais_separateurs, $bon_separateur, $numero);
    $tel = explode($bon_separateur, $x);
  }
  $resultat = $tel[0] . $bon_separateur 
  						. $tel[1] . $bon_separateur 
  						. $tel[2] . $bon_separateur 
  						. $tel[3] . $bon_separateur 
  						. $tel[4];
  return $resultat;
}

function formatter_num_tel_enregistrement($numero) {
	$resultat = "";
	$mauvais_separateurs = array(" ", ".", "-");
	$resultat = str_replace($mauvais_separateurs, "", $numero);
	return $resultat;
}

function formatter_date_affichage($date) {
	$resultat = "";
	$separateur = "/";
	$date = trim($date);
	if (strlen($date) === 10) {
		$annee = substr($date, -10, 4);
		$mois = substr($date, -5, 2);
		$jour = substr($date, -2, 2);
		if (($jour == "00") || ($mois == "00") || ($annee == "0000"))
			$resultat = ""; 
		else
			$resultat = $jour . $separateur . $mois . $separateur . $annee; 
	} else {
		$resultat = $date;
	}
	return $resultat;
}

function formatter_date_enregistrement($date) {
	$resultat = "";
	$separateur = "-";
	if (strlen($date) == 0) 
		$resultat = "0000-00-00";
	else 
		$resultat = substr($date, -4, 4) . $separateur 
								. substr($date, -7, 2) . $separateur 
								. substr($date, -10, 2);
	return $resultat;
}
?>
