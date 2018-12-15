// ============================================================================
// description : controle de la validite de la saisie d'une adresse mail
// utilisation : javascript - controleur formulaire web
// teste avec  : Mac OS 10.11
// contexte    : Site web du championnat de France 2018
// Copyright (c) 2017 AMP
// ----------------------------------------------------------------------------
// creation: 23-oct-2017 pchevaillier@gmail.com
// revision:
// ----------------------------------------------------------------------------
// commentaires :
// -
// attention :
// -
// a faire :
// ------------------------------------------------------------------------

function verif_courriel(element) {
  var est_correct = false;
  var x = element.value;
  if (x == null || x == "") {
    est_correct = true;
  } else {
    var regExpr = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
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

// ========================================================================
