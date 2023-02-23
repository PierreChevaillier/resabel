// ============================================================================
// contexte : Resabel - systeme de REServAtion de Bateau En Ligne
//            verification saisie utilisateur
// description : verifie si l'identifiant choisi par l'utlisateur (ou genere)
//  n'est pas deja utilise
// utilisation : javascript - controleur action element page web
// teste avec  : firefox, safari sur macOS 13.2,
//               jQuery 3.3.1
// dependances : JQuery
//               ids des elements (cf. formulaire_membre.php)
// copyright (c) 2017-2023 AMP. Tous droits reserves
// ----------------------------------------------------------------------------
// creation : 23-feb-2023 pchevaillier@gmail.com
// revision :
// ----------------------------------------------------------------------------
// commentaires :
// - en evolution
// attention :
// a faire :
// -
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
// Verification

/*
function traiter_valeur_retour_json(cle, valeur) {
  unique = false;
  switch (cle) {
    case 'status':
      status = JSON.parse(valeur);
      console.log("valeur retour: " + status);
      unique = (status == 1);
      if (!unique) {
        parent = element.parentElement;
        msg = document.createElement('p');
        msg.innerHTML = "valeur interdite : Identifiant déjà attribué";
        msg.style.color = 'red';
        parent.appendChild(msg);
        element.style.color = "red";
        element.focus();
      }
      break;
  }
  return unique;
}
*/
         
function verif_identifiant(element) {
  code_membre = 0;
  var champ_code = document.getElementById("code_mbr");
  code_membre = champ_code.value;
  couleur = element.style.color;
  if (couleur == '') couleur = 'black';
  identifiant = element.value;
  var parent = element.parentElement;
  const envoi = {code: code_membre, id: identifiant};
  console.log(couleur);
  var jqxhr = $.getJSON("php/scripts/verification_identifiant_unique.php?",
                        envoi,
                        function(retour) {
    //console.log("success verification_identifiant_unique.php");
    unique = false;
    $.each(retour, function(cle, valeur) {
      switch (cle) {
        case 'status':
          status = JSON.parse(valeur);
          console.log("valeur retour: " + status);
          unique = (status == 1);
          if (!unique) {
            msg = document.createElement('p');
            msg.innerHTML = "valeur interdite : Identifiant déjà attribué";
            msg.style.color = 'red';
            parent.appendChild(msg);
            element.style.color = "red";
            element.focus();
          } else {
            parent.removeChild(parent.lastChild);
            element.style.color = couleur;
          }
          break;
      }
    });
           return unique;
           }
           )
                       .fail(function(retour) {
                             //console.log("error verification_identifiant_unique.php");
                             return false;
                             });
  return true;
}


// ============================================================================
