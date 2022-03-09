// ============================================================================
// contexte    : Resabel V2
// description : requete ajax pour modifier l'etat actif (on non) d'un support d'activite
// utilisation : javascript - controleur action element page web
// teste avec  : firefox, safari sur Mac OS 10.14,
//               jQuery 3.3.1
// dependances : JQuery
//               modal.php (de Resabel) : ids des elements du composant
//               vue_support_activite.php : operation afficher_actions de Menu_Actions_Membre
// Copyright (c) 2017-2020 AMP. Tous droits reserves
// ----------------------------------------------------------------------------
// creation : 29-aug-2020 pchevaillier@gmail.com
// revision :
// ----------------------------------------------------------------------------
// commentaires :
// attention :
// a faire :
// ----------------------------------------------------------------------------

function requete_info_support_activite(code_support, modal_id) {
  envoi = {code: code_support};

  //alert("on est ici. Code = " + code_support + " Modal id " + modal_id);
  
  var jqxhr = $.getJSON("php/scripts/support_activite_info_obtenir.php?", envoi, function(retour) {
                        console.log( "success" );
                        $("#" + modal_id + "_titre").html("Support activité");
                        code_html = "<div>";
                        var items = [];
                        $.each( retour, function(cle, valeur) {
                               items.push(valeur + "<br />");
                               });
                        code_html += items.join("");
                        $("#" + modal_id + "_corps").html(code_html + "</div>");
                        
                        $("#" + modal_id + "_btn").html("Fermer");
                        var bouton = document.getElementById(modal_id + "_btn");
                        bouton.classList.add("btn-success");
                        console.log("fin affichage");
                        })
  .fail(function(retour) {
        console.log("error");
        })
  ;
}

// ============================================================================
