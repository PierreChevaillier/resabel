// ============================================================================
// contexte : Resabel - systeme de REServAtion de Bateau En Ligne
//            controle actions utilisateurice - cote client
// description : requete au serveur pour modifier l'etat actif (on non) d'un support d'activite
// Copyright (c) 2017-2023 AMP. Tous droits reserves.
// ----------------------------------------------------------------------------
// utilisation : javascript - controleur action element page web
// dependances :
// - modal.php (de Resabel) : ids des elements du composant
// - script execute par le serveur : nom et reponse
// - bootstrap (classe du bouton)
// utilise avec  :
//  - firefox, safari sur macOS 13.x
// ----------------------------------------------------------------------------
// creation : 29-aug-2020 pchevaillier@gmail.com
// revision : 23-mar-2023 pchevaillier@gmail.com jQuery  > XMLHttpRequest
// ----------------------------------------------------------------------------
// commentaires :
// attention :
// a faire :
// ----------------------------------------------------------------------------

function afficher_info_support_activite(modal_id, reponse) {
  var ok = true;
  
  const titre_modal = document.getElementById(modal_id + "_titre");
  const corps_modal = document.getElementById(modal_id + "_corps");
  const bouton_modal = document.getElementById(modal_id + "_btn");
  
  titre_modal.textContent = "Support activité";
  var code_html = "<div>";
  var items = [];
  var dict = JSON.parse(reponse);
  for (var entree in dict) {
    valeur = dict[entree];
    //console.log("JSON retour : " + entree + " =>" + valeur);
    items.push(valeur + "<br />");
  }
  code_html += items.join("");
  corps_modal.innerHTML = code_html + "</div>";
  bouton_modal.textContent = "Fermer";
  bouton_modal.classList.add("btn-success");
  return ok;
}

function requete_info_support_activite(code_support, modal_id) {
  const envoi = {code: code_support};

  var xmlhttp = new XMLHttpRequest();
  var url = "php/scripts/support_activite_info_obtenir.php?";
  const params = new URLSearchParams(envoi).toString();
  url += params;
  console.log(url);
  
  var ok = false;
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        ok = afficher_info_support_activite(modal_id, this.responseText);
      }
    };
  
  xmlhttp.open('GET', url, true);
  xmlhttp.send();
  return ok;
}

// ============================================================================
