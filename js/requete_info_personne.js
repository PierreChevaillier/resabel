// ============================================================================
// contexte    : Resabel V2
// description : Affichage requete ajax pour obtenir les informations sur une personne
// utilisation : javascript - controleur action element page web
// teste avec  : firefox, safari sur Mac OS 10.14
//               jQuery 3.3.1
// dependances : JQuery
// Copyright (c) 2017-2020 AMP. Tous droits reserves.
// ----------------------------------------------------------------------------
// creation : 27-avr-2019 pchevaillier@gmail.com
// revision :
// ----------------------------------------------------------------------------
// commentaires :
// attention :
// a faire :
// ----------------------------------------------------------------------------

function requete_info_personne(code_membre, modal_id) {
  envoi = {code: code_membre};
  //alert("on est ici. Code = " + code_membre + " id " + modal_body_id);
  var jqxhr = $.getJSON("php/scripts/membre_info_obtenir.php?", envoi, function(retour) {
                        console.log( "success" );
                        code_html = "<div>";
                        var items = [];
                        $.each( retour, function(cle, valeur) {
                               //items.push( "<p id='" + cle + "'>" + valeur + "</p>" );
                               items.push(valeur + "<br />");
                               });
                        code_html += items.join("");
                        $("#" + modal_id + "_corps").html(code_html + "</div>");
                        $("#" + modal_id + "_btn").html("Fermer");
                        document.getElementById(modal_id + "_btn").classList.add("btn-primary");
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
