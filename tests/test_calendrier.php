<?php
// ============================================================================
// contexte : Resabel - systeme de REServAtion de Bateau En Ligne
//            tests de composants
// description : essai de formatage des dates et heures
// copyright (c) 2018-2023 AMP. Tous droits reserves.
// ----------------------------------------------------------------------------
// utilisation : php -f <thisfile>
// dependances :
// utilise avec :
// - depuis 2023 :
//   PHP 8.2 sur macOS 13.x
//   PHP 8.1 sur serveur hebergement web
// ----------------------------------------------------------------------------
// creation : 27-dec-2018 pchevaillier@gmail.com
// revision : 31-mar-2023 pchevaillier@gmail.com Calendrier; IntlDateFormatter
// ----------------------------------------------------------------------------
// commentaires :
// - https://unicode-org.github.io/icu/userguide/format_parse/datetime/#datetime-format-syntax
// attention :
// -
// a faire :
// -
// ============================================================================
set_include_path('./../');

setlocale(LC_ALL, 'fr_FR.utf-8', 'french'); // pour Celendrier

// indispensable car autrement les instants sont en UTC (sur serveur web)
$tz_id = 'Europe/Paris';
date_default_timezone_set($tz_id);

$time_zone = IntlTimeZone::createTimeZone($tz_id);
echo "Time zone : " . $time_zone->getID() . PHP_EOL;

require_once 'php/metier/calendrier.php';

$aujourdhui = Calendrier::aujourdhui();
echo "Aujourd'hui (formate par Calendrier) : "
  . $aujourdhui->date_texte()
  . PHP_EOL;
echo "Aujourd'hui (formate par  DateTimeInterface::format) : "
  . $aujourdhui->format('l d F Y')
  . PHP_EOL;

$maintenant = Calendrier::maintenant();
echo "Maintenant (formate par Calendrier) : "
  . $maintenant->date_texte()
  . " " . $maintenant->heure_texte()
  . PHP_EOL;
echo "Maintenant (formate par  DateTimeInterface::format): "
  . $maintenant->format('l d F Y H:i:s')
  . PHP_EOL;

$locale = 'fr_FR';
$fmt = new IntlDateFormatter(
                             $locale,
                             IntlDateFormatter::FULL,
                             IntlDateFormatter::FULL,
                             $time_zone,
                             IntlDateFormatter::GREGORIAN);

$pattern_date_heure = 'EEEE d MMMM r HH:mm';
$fmt->setPattern($pattern_date_heure);
echo "Avec IntlDateFormatter (pattern : " . $pattern_date_heure . ") : " . $fmt->format($maintenant) . PHP_EOL;

$pattern_date_heure = 'EE d MMM HH:mm';
$fmt->setPattern($pattern_date_heure);
echo "Avec IntlDateFormatter (pattern : " . $pattern_date_heure . ") : " . $fmt->format($maintenant) . PHP_EOL;

//  $i1 = $cal->def_depuis_timestamp_sql("2018-10-08 20:05:47");
//  echo $i1->date_texte() . PHP_EOL; // lundi 8 octobre 2018
// echo $cal->heures_minutes_texte($i1) . "\n";
// ============================================================================
?>
