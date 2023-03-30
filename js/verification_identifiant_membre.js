// ============================================================================
// contexte : Resabel - systeme de REServAtion de Bateau En Ligne
//            verification saisie utilisateur
// description : verifie si l'identifiant choisi par l'utlisateur (ou genere)
//  n'est pas deja utilise
// copyright (c) 2017-2023 AMP. Tous droits reserves
// ----------------------------------------------------------------------------
// utilisation : javascript - controleur action element page web
// teste avec  : firefox, safari sur macOS 13.2,
//               jQuery 3.3.1
// dependances : JQuery
//               ids des elements (cf. formulaire_membre.php)
// ----------------------------------------------------------------------------
// creation : 23-feb-2023 pchevaillier@gmail.com
// revision : 24-feb-2023 pchevaillier@gmail.com MAJOR : jQUery > XMLHttpRequest
// ----------------------------------------------------------------------------
// commentaires :
// - en evolution
// attention :
// a faire :
// - meilleure gestion message (utilisation d'une classe rsbl_msg_err)
// - afficher message erreur si caracteres non valides
// - code retour si pas bon : pas bon ou pas unique => false
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------

function supprimer_message_erreur(element) {
  const parent = element.parentNode;
  const children = parent.childNodes;
  const msgNodeId = element.id + '_msg';
  element.style.color = 'black';
  for (let i = 0; i < children.length; i++) {
    if (children[i].id == msgNodeId) {
      parent.removeChild(children[i]);
    }
  }
  return;
}

/*
function verifier_format_identifiant(element) {
  const x = element.value;
  var est_correct = false;
  const regExpr = new RegExp("^[a-zA-Z0-9\.\-]+$");
  if (!regExpr.test(x)) {
    element.style.color = "orange";
    element.focus();
  } else {
    est_correct = true;
  }
  if (est_correct) {
    element.style.color = "black";
  }
  return est_correct;
}
*/
function verifier_format_identifiant(element) {
  const regExpr = new RegExp("^[a-zA-Z0-9\.\-]+$");
  const ok = regExpr.test(element.value);
  element.style.color = ok? "black": "orange";
  if (!ok)
    element.focus();
  return ok;
}

function traiter_reponse(element, reponse) {
  var unique = false;
  const parent = element.parentElement;
  var dict = JSON.parse(reponse);
  for (var entree in dict) {
    valeur = dict[entree];
    console.log("JSON retour : " + entree + " =>" + valeur);
    
    switch (entree) {
      case 'status':
        unique = (valeur === 1);
        if (!unique) {
          msg = document.createElement('p');
          msg.id = element.id + '_msg';
          msg.className('text-danger rsbl-msg-err');
          if (valeur === 0)
            msg.innerHTML = "valeur interdite : identifiant déjà attribué";
          else if (valeur === 2)
            msg.innerHTML = "valeur interdite : caractères autorisés : lettres, chiffres, . ou -";
          parent.appendChild(msg);
          element.style.color = "red";
          element.focus();
        } else {
          element.style.color = "black";
          console.log("unique");
        }
        break;
    }
  }
  return unique;
}

function verif_identifiant(element) {
  supprimer_message_erreur(element);
  var ok = verifier_format_identifiant(element);
  if (!ok) return false;
  
  const champ_code = document.getElementById("code_mbr");
  const code_membre = champ_code.value;
  const identifiant = element.value;
  
  const envoi = {code: code_membre, id: identifiant};
  console.log(envoi);
  
  var xmlhttp = new XMLHttpRequest();
  var url = "php/scripts/verification_identifiant_unique.php?";
  const params = new URLSearchParams(envoi).toString();
  url += params;
  console.log(url);
  
  ok = false;
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        ok = traiter_reponse(element, this.responseText);
      }
    };
  
  xmlhttp.open('GET', url, true);
  xmlhttp.send();
  console.log("request sent");
  return ok;
}

// ============================================================================
