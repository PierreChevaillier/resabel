// ============================================================================
// contexte    : Resabel V2
// description : requete ajax pour modifier l'inscription
//               a une seance d'activite
// utilisation : javascript - controleur action element page web
// teste avec  : firefox, safari sur Mac OS 10.14,
//               jQuery 3.3.1
// dependances : JQuery
//               modal.php (de Resabel) : ids des elements du composant
//               page affichee ensuite dans Resabel
// Copyright (c) 2017-2020 AMP. Tous droits reserves
// ----------------------------------------------------------------------------
// creation : 29-fev-2020 pchevaillier@gmail.com
// revision : 08-mar-2020 pchevaillier@gmail.com
// ----------------------------------------------------------------------------
// commentaires :
// actions supportees :
// ii : inscription individuelle
// di : desinscription individuelle
// attention :
// a faire :
// - traiter le code de retour de facon a indiquer que l'action s'est mal passee
// - suppprimer les logs.
// ----------------------------------------------------------------------------

function requete_inscription_individuelle(modal_id, code_action, code_seance, code_site, code_support, debut, fin, code_personne, responsable) {
  //alert("on est ici Modal : " + modal_id + " action : " + code_action + " support : " + code_support);
  envoi = {act: code_action, id: code_seance, sa: code_site, s: code_support, deb: debut, fin: fin, p: code_personne, resp: responsable};
  
  var jqxhr = $.getJSON("php/scripts/inscription_seance_activite_maj.php?", envoi, function(retour) {
                        console.log( "success" );
                        if (code_action == "ii")
                          $("#" + modal_id + "_titre").html("Inscription à une séance");
                        else if (code_action == "di")
                          $("#" + modal_id + "_titre").html("Annulation inscription");
                        else
                          $("#" + modal_id + "_titre").html("ERREUR : Type operation inconnu... ");
                        $("#" + modal_id + "_corps").html("<div><p>Opération réalisée avec succès</p></div>");
                        $("#" + modal_id + "_btn").html("Fermer");
                        document.getElementById(modal_id + "_btn").addEventListener("click", function() { document.location.href="accueil_perso.php"});
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
