// ============================================================================
// description : essai ajax
// utilisation : javascript - controleur action element page web
// teste avec  : firefox, safari sur Mac OS 10.14
// contexte    : Resabel V2
// Copyright (c) 2017-2019 AMP. Tous droits reserves
// ----------------------------------------------------------------------------
// creation : 29-mar-2019 pchevaillier@gmail.com
// revision :
// ----------------------------------------------------------------------------
// commentaires :
// -
// attention :
// -
// a faire :
// ----------------------------------------------------------------------------
function requete_ajax(element, code_membre, valeur) {
  // https://openclassrooms.com/fr/courses/1567926-un-site-web-dynamique-avec-jquery/1569648-le-fonctionnement-de-ajax
  // voir
  // https://stackoverflow.com/questions/19663555/bootstrap-3-how-to-load-content-in-modal-body-via-ajax
  // pour affichage modal
  //alert("Coucou");
  //affichage_statut_object = document.getElementById("statut_XMLHTTPRequest");
  /*
   var jqxhr = $.ajax( "example.php" )
   .done(function() {
   alert( "success" );
   })
   .fail(function() {
   alert( "error" );
   })
   .always(function() {
   alert( "complete" );
   });
   
   // Perform other work here ...
   
   // Set another completion function for the request above
   jqxhr.always(function() {
   alert( "second complete" );
   });
   */
  
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
