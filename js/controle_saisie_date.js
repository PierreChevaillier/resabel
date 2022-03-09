// ============================================================================
// description : controle de la validite d'un numero d'une date saisie
// utilisation : javascript - controleur formulaire web
// teste avec  : Mac OS 10.11
// contexte    : Site web du championnat de France 2018
// Copyright (c) 2018 AMP
// ----------------------------------------------------------------------------
// creation: 07-dec-2018 pchevaillier@gmail.com (de resabel v1)
// revision:
// ----------------------------------------------------------------------------
// commentaires :
// -
// attention :
// -
// a faire :
//
// ------------------------------------------------------------------------

function verif_date(element) {
  var est_correct = false;
  var x = element.value;
  if (x == null || x == "") {
    est_correct = true;
  } else {
    var regExpr = /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]|(?:Jan|Mar|May|Jul|Aug|Oct|Dec)))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2]|(?:Jan|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec))\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)(?:0?2|(?:Feb))\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9]|(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep))|(?:1[0-2]|(?:Oct|Nov|Dec)))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/;
    if (!reg.test(x)) {
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

function verif_annee(element) {
  var est_correct = false;
  var x = element.value;
  if (x == null || x == "") {
    est_correct = true;
  } else {
    var i = parseInt(x);
    est_correct = (i > 1900 && i < 2099);
  }
  if (est_correct) {
     element.style.color = "black";
  } else {
    element.style.color = "red";
  }
   return est_correct;
}

// ========================================================================
