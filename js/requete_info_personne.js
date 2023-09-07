// ============================================================================
// contexte : Resabel - systeme de REServAtion de Bateau En Ligne
//            controle actions utilisateurice - cote client
// description : requete au serveur infos. participation seance d'activite
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
// creation : 27-avr-2019 pchevaillier@gmail.com
// revision : 23-mar-2023 pchevaillier@gmail.com jQuery > XMLHttpRequest
// ----------------------------------------------------------------------------
// commentaires :
// attention :
// a faire :
// ----------------------------------------------------------------------------

function afficher_info_personne(modal_id, reponse) {
  var ok = false;
  
  const modal = document.getElementById(modal_id);
  const titre_modal = document.getElementById(modal_id + "_titre");
  const corps_modal = document.getElementById(modal_id + "_corps");
  const bouton_modal = document.getElementById(modal_id + "_btn");
  
  titre_modal.textContent = "Contact";
  var code_html = "<div>";
  var items = [];
  var dict = JSON.parse(reponse);
  for (var entree in dict) {
    valeur = dict[entree];
    console.log("JSON retour : " + entree + " =>" + valeur);
    items.push(valeur + "<br />");
  }
  code_html += items.join("");
  corps_modal.innerHTML = code_html + "</div>";
  bouton_modal.textContent = "Fermer";
  bouton_modal.classList.add("btn-success");

  return ok;
}

function requete_info_personne(code_membre, modal_id) {
  const envoi = {code: code_membre};
  
  var xmlhttp = new XMLHttpRequest();
  var url = "php/scripts/membre_info_obtenir.php?";
  const params = new URLSearchParams(envoi).toString();
  url += params;
  
  var ok = false;
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        ok = afficher_info_personne(modal_id, this.responseText);
      }
    };
  
  xmlhttp.open('GET', url, true);
  xmlhttp.send();
  return ok;
}

// ============================================================================
