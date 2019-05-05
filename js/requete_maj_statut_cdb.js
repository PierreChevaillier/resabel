// ============================================================================
// contexte    : Resabel V2
// description : requete ajax pour modifier le statut de chef de bord de la personne
// utilisation : javascript - controleur action element page web
// teste avec  : firefox, safari sur Mac OS 10.14,
//               jQuery 3.3.1
// dependances : JQuery
//               modal.php (de Resabel) : ids des elements du composant
//               vue_personne.php : operation afficher_actions de Menu_Actions_Membre
// Copyright (c) 2017-2019 AMP. Tous droits reserves
// ----------------------------------------------------------------------------
// creation : 01-mai-2019 pchevaillier@gmail.com depuis essai_ajax.js
// revision :
// ----------------------------------------------------------------------------
// commentaires :
// attention :
// a faire :
// ----------------------------------------------------------------------------

function requete_maj_statut_cdb(item_menu, code_membre, nouveau_statut, modal_id) {
  
  envoi = {code: code_membre, cdb: nouveau_statut};

  //alert("on est ici. Code = " + code_membre + " id " + modal_body_id);
  var jqxhr = $.getJSON("php/scripts/membre_statut_cdb_maj.php?", envoi, function(retour) {
                        console.log( "success" );
                        $("#" + modal_id + "_titre").html("Modification statut Chef de bord");
                        code_html = "<div>";
                        var items = [];
                        $.each( retour, function(cle, valeur) {
                               items.push(valeur + "<br />");
                               });
                        code_html += items.join("");
                        $("#" + modal_id + "_corps").html(code_html + "</div>");
                        $("#" + modal_id + "_btn").html("Fermer");
                        document.getElementById(modal_id + "_btn").classList.add("btn-success");
                        
                        // Change l'option du menu (bascule)
                        while (item_menu.firstChild) {
                          item_menu.removeChild(item_menu.firstChild);
                        }
                        var texte;
                        if (nouveau_statut == 0) {
                          texte = document.createTextNode('Passer chef de bord');
                          item_menu.setAttribute("onclick", "return requete_maj_statut_cdb(this, " + code_membre + ", 1, 'aff_msg');");
                        } else {
                          texte = document.createTextNode('Plus chef de bord');
                          item_menu.setAttribute("onclick", "return requete_maj_statut_cdb(this, " + code_membre + ", 0, 'aff_msg');");
                        }
                        item_menu.appendChild(texte);
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
