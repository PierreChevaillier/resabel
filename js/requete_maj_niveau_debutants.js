// ============================================================================
// contexte : Resabel - systeme de REServAtion de Bateau En Ligne
//            controle actions utilisateurice - cote client
// description : requete au serveurpour modifier le niveau des debutants
// Copyright (c) 2017-2023 AMP. Tous droits reserves.
// ----------------------------------------------------------------------------
// utilisation : javascript - controleur action element page web
// dependances :
// - script execute par le serveur : nom
// - bootstrap (classe du bouton)
// utilise avec  :
//  - firefox, safari sur macOS 13.x
// ----------------------------------------------------------------------------
// creation : 04-mai-2019 pchevaillier@gmail.com depuis essai_ajax.js
// revision : 30-mar-2023 pchevaillier@gmail.com jQuery > XMLHttpRequest
// ----------------------------------------------------------------------------
// commentaires :
// attention :
//  - PAS TESTE
// a faire :
// ----------------------------------------------------------------------------

function requete_maj_niveau_debutants(modal_id) {
  const envoi = {niv: 1}; // inutilise
  var xmlhttp = new XMLHttpRequest();
  var url = "php/scripts/debutants_niveau_maj.php?";
  const params = new URLSearchParams(envoi).toString();
  url += params;
  
  var ok = false;
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        const titre_modal = document.getElementById(modal_id + "_titre");
        const corps_modal = document.getElementById(modal_id + "_corps");
        const bouton_modal = document.getElementById(modal_id + "_btn");
        titre_modal.textContent = "Modification du statut des débutants");
        corps_modal.innerHTML = "<div><p>Opération réalisée avec succès</p></div>";
        bouton_modal.textContent = "Fermer";
        bouton_modal.classList.add("btn-success");
      }
    };
  
  xmlhttp.open('GET', url, true);
  xmlhttp.send();
  return ok;
}

// ============================================================================
