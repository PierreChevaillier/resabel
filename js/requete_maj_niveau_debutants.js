// ============================================================================
// contexte    : Resabel V2
// description : requete ajax pour modifier le niveau des debutants
// utilisation : javascript - controleur action element page web
// teste avec  : firefox, safari sur Mac OS 10.14,
//               jQuery 3.3.1
// dependances : JQuery
//               modal.php (de Resabel) : ids des elements du composant
//               debutants.php : page avec le bouton pour lancer la requete
// Copyright (c) 2017-2019 AMP. Tous droits reserves
// ----------------------------------------------------------------------------
// creation : 04-mai-2019 pchevaillier@gmail.com depuis essai_ajax.js
// revision :
// ----------------------------------------------------------------------------
// commentaires :
// attention :
// a faire :
// ----------------------------------------------------------------------------

function requete_maj_niveau_debutants(modal_id) {
  envoi = {niv: 1}; // inutilise
  var jqxhr = $.getJSON("php/scripts/debutants_niveau_maj.php?", envoi, function(retour) {
                        console.log( "success" );
                        $("#" + modal_id + "_titre").html("Modification du statut des débutants");
                        $("#" + modal_id + "_corps").html("<div><p>Opération réalisée avec succès</p></div>");
                        $("#" + modal_id + "_btn").html("Fermer");
                        document.getElementById(modal_id + "_btn").classList.add("btn-success");
                        console.log("fin affichage");
                        })
  .done(function(retour) {
        console.log( "second success" );
        //$("#statut_requete_json").html("<p>C'est un succès !</p>");
        })
  .fail(function(retour) {
        console.log("error");
        //$("#statut_requete_json").html("<p>Oups: ça ce marche pas...</p>");
        })
  .always(function(retour) {
          console.log( "complete" );
          });
}

// ============================================================================
