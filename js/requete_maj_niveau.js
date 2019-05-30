// ============================================================================
// contexte    : Resabel V2
// description : requete ajax pour modifier le niveau de la personne
// utilisation : javascript - controleur action element page web
// teste avec  : firefox, safari sur Mac OS 10.14,
//               jQuery 3.3.1
// dependances : JQuery
//               modal.php (de Resabel) : ids des elements du composant
//               vue_personne.php : operation afficher_actions de Menu_Actions_Membre
//               page_personnes : id du formulaire de selection des personnes
// Copyright (c) 2017-2019 AMP. Tous droits reserves
// ----------------------------------------------------------------------------
// creation : 05-mai-2019 pchevaillier@gmail.com
// revision :
// ----------------------------------------------------------------------------
// commentaires :
// attention :
// a faire :
// ----------------------------------------------------------------------------

function requete_maj_niveau(item_menu, code_membre, nouveau_niveau, modal_id) {
  
  envoi = {code: code_membre, niv: nouveau_niveau};

  //alert("on est ici. Code = " + code_membre + " id " + modal_body_id);
  var jqxhr = $.getJSON("php/scripts/membre_niveau_maj.php?", envoi, function(retour) {
                        console.log( "success" );
                        if (nouveau_niveau == 2)
                          $("#" + modal_id + "_titre").html("Modification du niveau");
                        else
                          $("#" + modal_id + "_titre").html("Modification du niveau");
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
                        // Quand on modifie les proprietes d'une personne, elle ne correspond
                        // peut-etre plus aux personnes selectionnees
                        // il faut donc soumettre a nouveau le formulaire de selection
                        var formulaire = document.getElementById("form_sel_prs");
                        bouton.addEventListener("click", function() { formulaire.submit(); });
                        /*
                        // Change l'option du menu (bascule)
                        while (item_menu.firstChild) {
                          item_menu.removeChild(item_menu.firstChild);
                        }
                        var texte;
                        if (nouveau_niveau < 2) {
                          texte = document.createTextNode('Passer non débutant');
                          item_menu.setAttribute("onclick", "return requete_maj_niveau(this, " + code_membre + ", 2, 'aff_msg');");
                        } else {
                          texte = document.createTextNode('Repasser débutant');
                          item_menu.setAttribute("onclick", "return requete_maj_niveau(this, " + code_membre + ", 1, 'aff_msg');");
                        }
                        item_menu.appendChild(texte);
                         */
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
