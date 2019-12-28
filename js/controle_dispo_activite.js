/* ------------------------------------------------------------------------
 * contexte : Resabel - systeme de REServation de Bateaux En Ligne
 * description : modifications contextuelle des options des champs 'select'
 *               du formulaire de recherche des disponibilités pour
 *               inscription a une activite
 * copyright (c) 2018-2019 AMP. Tous droits reserves.
 * --------------------------------------------------------------------------
 * utilisation : javascript (formulaire)
 * dependences : formulaire_dispo_activite.php (champs du formualire)
 * creation: 22-jul-2019 pchevaillier@gmail.com
 * revision: 08-sep-2019 pchevaillier@gmail.com logique MaJ contectuelle champs saisie
 * ----------------------------------------------------------------------------
 * commentaires : 
 * attention : 
 * a faire :
 * ----------------------------------------------------------------------------
 */

function chercher_info_site(champ_date, champ_site, champ_prem_creneau, champ_dern_creneau, champ_type_support, champ_support) {
  var est_correct = false;
  var x = champ_site.value;
  envoi = {'sa': champ_site.value, 'j': champ_date.value}
  
  alert('site ' + x + ' supports ' + champ_support.options);

  champ_type_support.options.length = 0;
  champ_type_support.add(new Option("Tous", 0));

  champ_support.options.length = 0;
  champ_support.add(new Option("Tous", 0));
  
  var jqxhr = $.getJSON("php/scripts/site_activites_info_obtenir.php?", envoi, function(retour) {
                        console.log( "success" );
                        code_html = "<div>";
                        var items = [];
                        $.each( retour, function(cle, valeur) {
                               switch (cle) {
                               /*
                               case 'pc':
                               champ_prem_creneau  = valeur;
                               case 'dc':
                               champ_dern_creneau = valeur;
                                */
                               case 'ts':
                               choix = JSON.parse(valeur);
                               $.each(choix, function(code, libelle) {
                                      champ_type_support.add(new Option(libelle, code));
                                      })

                               case 's':
                               choix_supports = JSON.parse(valeur);
                               $.each(choix_supports, function(code_support, libelle_support) {
                                      champ_support.add(new Option(libelle_support, code_support));
                                      })
                               
                               default:
                               items.push( "<p>" + cle + ": " + valeur + "</p>" );
                               }
                              
                               });
                        code_html += items.join("");
                        //$("body").html(code_html + "</div>");
                        console.log("fin maj champ");
                        })
  .done(function(retour) {
        console.log( "second success" );
        est_correct = true;
        //$("#statut_requete_json").html("<p>C'est un succès !</p>");
        })
  .fail(function(retour) {
        console.log("error");
        //$("#statut_requete_json").html("<p>Oups: ça ne marche pas...</p>");
        })
  .always(function(retour) {
          console.log( "complete" );
          });
  return est_correct;
}

function tutu(champ_site) {
  var est_correct = false;
  var x = champ_site.value;
  alert('tutu' + x);
  est_correct = true;
  return est_correct;
}

// Concue pour etre appele au chargement de la page
function creer_gestionnaire_evenement(id_date, id_site, id_prem_creneau, id_dern_creneau, id_type_support, id_support) {
  var champ_date = document.getElementById(id_date);
  var champ_site = document.getElementById(id_site);
  var champ_prem_creneau = document.getElementById(id_prem_creneau);
  var champ_dern_creneau = document.getElementById(id_dern_creneau);
  var champ_type_support = document.getElementById(id_type_support);
  var champ_support = document.getElementById(id_support);
  //alert("Chargement site " + champ_site.value)
  champ_site.addEventListener("change", function() { chercher_info_site(champ_date, champ_site, champ_prem_creneau, champ_dern_creneau, champ_type_support, champ_support); });
  //champ_site.addEventListener("change", function() { tutu(champ_site); });
  return true;
  
}
