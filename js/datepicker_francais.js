/* ------------------------------------------------------------------------
 * contexte : Resabel - systeme de REServation de Bateaux En Ligne
 * description : controle saisie date - parametrage pour francais
 * copyright (c) 2019-2020 AMP. Tous droits reserves.
 * --------------------------------------------------------------------------
 * utilisation : javascript
 * creation : 01-jan-2020 pchevaillier@gmail.com
 * revision :
 * ------------------------------------------------------------------------
 * commentaires : 
 * attention : 
 * a faire :
 * - des tests et des tests
 * -----------------------------------------------------------------------
 */

$(function() {
$( "#datepicker" ).datepicker({
    altField: "#datepicker",
    closeText: 'Fermer',
    prevText: 'Précédent',
    nextText: 'Suivant',
    currentText: 'Aujourd\'hui',
    monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
    monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
    dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
    dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
    dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
    weekHeader: 'Sem.',
    dateFormat: 'yy-mm-dd'
    });
});

