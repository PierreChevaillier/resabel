// ============================================================================
// description : controle de la validite de la saisie d'un nom
//               prenom, nom de famille, nom de ville...
// utilisation : javascript - controleur formulaire web
// teste avec  : firefox, safari sur Mac OS 10.14
// contexte    : Site web du championnat de France 2018, resabel V2 (depuis dec-2018)
// Copyright (c) 2017-2019 AMP. Tous droits reserves
// ----------------------------------------------------------------------------
// creation : 23-oct-2017 pchevaillier@gmail.com
// revision : 29-dec-2018 pchevaillier@gmail.com autorisation ñì
// revision : 06-jan-2019 pchevaillier@gmail.com suppression message erreur
// revision : 15-jul-2024 pchevaillier@gmail.com autorisation ü (Salaün)
// ----------------------------------------------------------------------------
// commentaires :
// -
// attention :
// -
// a faire :
// ----------------------------------------------------------------------------

function supprimer_message_erreur(element) {
  var parent = element.parentNode;
  var children = parent.childNodes;
  var msgNodeId = element.name + '_msg';
  for (var i = 0; i < children.length; i++) {
    if (children[i].id == msgNodeId) {
      parent.removeChild(children[i]);
      break;
    }
  }
  return;
}

function verif_nom(element) {
  // en cas de nouvelle saisie : il faut supprimer l'eventuel message d'erreur
  supprimer_message_erreur(element);
  
  var est_correct = false;
  var x = element.value;
  if (x == null || x == "") {
    est_correct = true;
  } else {
    const pattern = "^[a-zA-Zéèëçñìïü\ '-]+$";
    ok = new RegExp(pattern).test(x);
    if (!ok) {
      element.style.color = "red";
      element.focus();
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
