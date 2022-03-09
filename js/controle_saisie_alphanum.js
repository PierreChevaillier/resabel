// ============================================================================
// description : controle de la validite d'un champ alphanumerique
// utilisation : javascript - controleur formulaire web
// teste avec  : Mac OS 10.14
// Copyright (c) 2018-2020 AMP. Tous droits reserves
// ----------------------------------------------------------------------------
// creation: 03-sep-2020 pchevaillier@gmail.com
// revision:
// ----------------------------------------------------------------------------
// commentaires :
//  - champ vide accepte comme correct
// attention :
// a faire :
// ------------------------------------------------------------------------

function verif_alphanum(element) {
  var est_correct = false;
  var x = element.value;
  if (x == null || x == "") {
    est_correct = true;
  } else {
    var reg = /^[0-9a-zA-Z]+$/;
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

// ========================================================================
