// ============================================================================
// contexte    : Resabel V2
// description : convertit une date html en timestamp Unix
// utilisation : javascript - controleur action element page web
// teste avec  : firefox, safari sur Mac OS 10.14,
// dependances :
// Copyright (c) 2017-2019 AMP. Tous droits reserves
// ----------------------------------------------------------------------------
// creation : 07-jul-2019 pchevaillier@gmail.com
// revision :
// ----------------------------------------------------------------------------
// commentaires :
// attention :
// a faire :
// ----------------------------------------------------------------------------

function convertir_date_timestamp(elem_date, elem_timestamp) {
  var date= document.getElementById(elem_date);
  var ts = document.getElementById(elem_timestamp);
  ts.value = Date.parse(date.value) / 1000;
  return true;
}

// ============================================================================
