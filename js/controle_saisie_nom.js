// ============================================================================
// description : controle de la validite de la saisie d'un nom
//               prenom, nom de famille, nom de ville...
// utilisation : javascript - controleur formulaire web
// teste avec  : firefox, safari sur Mac OS 10.11
// contexte    : Site web du championnat de France 2018
// Copyright (c) 2017 AMP. Tous droits reserves
// ----------------------------------------------------------------------------
// creation : 23-oct-2017 pchevaillier@gmail.com
// revision :
// ----------------------------------------------------------------------------
// commentaires :
// -
// attention :
// -
// a faire :
// ----------------------------------------------------------------------------

function verif_nom(element) {
  var est_correct = false;
  var x = element.value;
  if (x == null || x == "") {
    est_correct = true;
  } else {
    var regExpr = /^[a-zA-Zéèëç\ '-]+$/;
    if (!regExpr.test(x)) {
      element.style.color = "red";
      element.focus(); // marche pas...
    } else {
      est_correct = true;
    }
  }
  if (est_correct) {
    element.style.color = "black";
  }
  return est_correct;
}

// ============================================================================
