// ============================================================================
// contexte    : Resabel V2
// description : essai ajax
// utilisation : javascript - controleur action element page web
// teste avec  : firefox, safari sur Mac OS 10.14,
//               jQuery 3.3.1
// dependances : JQuery
// Copyright (c) 2017-2019 AMP. Tous droits reserves
// ----------------------------------------------------------------------------
// creation : 29-mar-2019 pchevaillier@gmail.com
// revision : 01-avr-2019 pchevaillier@gmail.com requete_ajax_json
// ----------------------------------------------------------------------------
// commentaires :
// pour affichage modal, voir
// https://stackoverflow.com/questions/19663555/bootstrap-3-how-to-load-content-in-modal-body-via-ajax
// insertBefore
// parentNode (ou parentElement : pas sur que ca marche sur safari Ios)
// attention :
// -
// a faire :
// ----------------------------------------------------------------------------
function creer_elements_modal(modalId, parentId)  {
  var parent = document.getElementById(parentId);
  console.log(parent.classList);
  var div =  document.createElement('div');
  div.id = modalId;
  div.classList.add("modal", "rsbl");
  parent.appendChild(div);
  
  parent = div;
  var elem= document.createElement('div');
  elem.classList.add('modal-dialog');
  parent.appendChild(elem);
  
  parent = elem;
  var content = document.createElement('div');
  content.classList.add('modal-content');
  parent.appendChild(content);

  elem = document.createElement('div');
  elem.classList.add('modal-header');
  content.appendChild(elem);
  
  parent = elem;
  elem = document.createElement('h4');
  elem.classList.add('modal-title');
  parent.appendChild(elem);
  var texte = document.createTextNode('Titre modal');
  elem.appendChild(texte);
  
  elem = document.createElement('div');
  elem.classList.add('modal-body');
  elem.id = modalId + 'Body';
  content.appendChild(elem);
  
  elem = document.createElement('div');
  elem.classList.add('modal-footer');
  content.appendChild(elem);
  
  parent = elem;
  
  // <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
  
  
}


function requete_ajax_json(element, code_membre, valeur) {
  envoi = {code: code_membre, niveau: valeur};
  
  
  var jqxhr = $.getJSON("php/tests/test_ajax_serveur.php?fmt=json", envoi, function(retour) {
                        console.log( "success" );
                        var code_html = "<p>code_html : <ul />";
                        var items = [];
                        $.each( retour, function(cle, valeur) {
                               items.push( "<li id='" + cle + "'>" + cle + " : " + valeur + "</li>" );
                               });
                        code_html += items.join("");
                        $("#result_json").html(code_html + "<ul>");
                        console.log("fin affichage");
                        })
    .done(function(retour) {
        console.log("second success");
        $("#statut_requete_json").html("<p>C'est un succès !</p>");
        })
    .fail(function(retour) {
        console.log( "error" );
          $("#statut_requete_json").html("<p>Oups: ça ce marche pas...</p>");
        })
    .always(function(retour) {
          console.log( "complete" );
          });
}

function requete_json_modal(element, code_membre, valeur, modal_body_id) {
  envoi = {code: code_membre, niveau: valeur};
  alert("on est ici. Code = " + code_membre + " id " + modal_body_id);
  var jqxhr = $.getJSON("php/tests/test_ajax_serveur.php?fmt=json", envoi, function(retour) {
                        console.log( "success" );
                        code_html = "<p>code_html : <ul>";
                        var items = [];
                        $.each( retour, function(cle, valeur) {
                               items.push( "<li id='" + cle + "'>" + cle + " : " + valeur + "</li>" );
                               });
                        code_html += items.join("");
                        $("#" + modal_body_id).html(code_html + "</ul></p>");
                        console.log("fin affichage");
                        })
  .done(function(retour) {
        console.log( "second success" );
        //$("#statut_requete_json").html("<p>C'est un succès !</p>");
        })
  .fail(function(retour) {
        console.log( "error" );
        //$("#statut_requete_json").html("<p>Oups: ça ce marche pas...</p>");
        })
  .always(function(retour) {
          console.log( "complete" );
          });
}

function requete_ajax(element, code_membre, valeur) {
  // https://openclassrooms.com/fr/courses/1567926-un-site-web-dynamique-avec-jquery/1569648-le-fonctionnement-de-ajax
  
  $.ajax({
         url : '../php/tests/test_ajax_serveur.php',
         type : 'GET',
         data : 'mbr=' + code_membre + '&v=' + valeur,
         dataType : 'html', // format donnees retournees par le script serveur
         // statut: statut de la requete (fourni par JQuery)
         success: function(code_html, texte_statut) {
         $("#result_ajax").html("<p>code_html : " + code_html + "</p>");
         //$("#statut_requete").html("<p>statut : " + texte_statut + "</p>");
         //$(code_html).appendTo("#result_ajax");
         },
         
         error : function(resultat, texte_statut, erreur) {
         $("#statut_requete").html("<p>statut : " + texte_statut + "</p>");
         },
         
         complete : function(resultat, texte_statut) {
         $("#statut_requete").html("<p>statut : " + texte_statut + "</p>");
         }
         });
}
// ============================================================================
