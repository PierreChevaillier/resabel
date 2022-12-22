<!DOCTYPE html>
  <html lang="fr">
    <head>
      <meta charset="utf-8" />
      <title>Test DateTime PHP</title>
    </head>
    <body>
      <?php
//        date_default_timezone_set('Europe/Paris');
        //setlocale(LC_ALL, 'fr_FR', 'french');
        setlocale(LC_ALL, 'fr_FR.utf-8', 'french');
        ?>
      <h1>Maintenant</h1>
      <?php
        echo '<p>Time zone non précisée : ', date_default_timezone_get(), '</p>';
        $maintenant = new DateTime("now");
        echo $maintenant->format('d-m-Y H:i:s');
        
        date_default_timezone_set('Europe/Paris');
        $timeZone = new DateTimeZone('Europe/Paris');
        $maintenant = new DateTime("now");
        echo '<p>Time zone defini : ', date_default_timezone_get(), '</p>';
        echo $maintenant->format('d-m-Y H:i:s');
        
        echo ' ' , strftime('%A %d %B %Y', $maintenant->getTimestamp());
        echo ' ' , strftime('%a %d %h %Y', $maintenant->getTimestamp());
      ?>
      <h1>Jours</h1>
      <?php
        $j = new DateTime("today", $timeZone);
        $aujourdhui = DateTimeImmutable::createFromMutable($j);
        echo '<p>Aujourdhui : ', $aujourdhui->format('d-m-Y H:i:s'), '</p>', PHP_EOL;
        
        echo '<p>Jour de la semaine : ', $j->format('N'), '</p>', PHP_EOL;
        echo '<p>Numéro de semaine : ', $j->format('W'), '</p>', PHP_EOL;
        
        $demain = new DateTime('today +1 days', $timezone);
        echo '<p>Demain : ', $demain->format('d-m-Y H:i:s'), '</p>', PHP_EOL;
        
        $hier = new DateTime('today -1 days', $timezone);
        echo '<p>Hier : ', $hier->format('d-m-Y H:i:s'), '</p>', PHP_EOL;
        
        $semaine = new DateInterval('P7D');
        $dans_une_semaine = $aujourdhui->add($semaine);
        echo '<p>Dans une semaine : ', $dans_une_semaine->format('d-m-Y H:i:s'), '</p>', PHP_EOL;
        echo '<p>Il y a  une semaine : ', $aujourdhui->sub($semaine)->format('d-m-Y H:i:s'), '</p>', PHP_EOL;
        ?>

      <h1>Heures dans la journée</h1>
      <?php
        $j = new DateTime("today", $timeZone);
        echo $j->format('d-m-Y H:i:s');
        $d = DateTimeImmutable::createFromMutable($j);
        
        $h1 = new DateInterval('PT7H30M00S');
        //$j1 = new DateTime($j->format('Y-m-d'), $timeZone);
        $j1 = $d->add($h1);
        
        echo '<p> Jour : ' , $j->format('d-m-Y H:i:s'),  '</p>';
        echo '<p> Ce jour à  7H30M : ', $j1->format('d-m-Y H:i:s');
      
        ?>
      
      <h1>Créneaux horaires</h1>
      <?php
        $j = new DateTime("now", $timeZone);
        $j->setTime(0, 0, 0);
        echo $j->format('d-m-Y H:i:s');
        
        $d = DateTimeImmutable::createFromMutable($j);
        $debut = new DateInterval('PT6H00M00S');
        $fin = new DateInterval('PT23H00M00S');
        
        $dfin = $d->add($fin);
        $interval = new DateInterval('PT1H00M00S');
        
        //$t_lever = date_sunrise(time(), SUNFUNCS_RET_TIMESTAMP, 48.35, -4.68);
        $t_lever = date_sunrise($j->getTimestamp(), SUNFUNCS_RET_TIMESTAMP, 48.35, -4.68);
        $d_lever = new DateTime("now", $timeZone);
        $d_lever->setTimestamp($t_lever);
        echo "<p>Lever : " , $d_lever->format('d-m-Y H:i:s'), "</p>";
        
        $t_coucher = date_sunset($j->getTimestamp(), SUNFUNCS_RET_TIMESTAMP, 48.35, -4.68);
        $d_coucher = new DateTime("now", $timeZone);
        $d_coucher->setTimestamp($t_coucher);
        echo "<p>coucher : " , $d_coucher->format('d-m-Y H:i:s'), "</p>";
        
        $h1 = $d->add($debut);
        $h2 = $d->add($debut);
        $h2 = $h2->add($interval);
        while ($h2 <= $dfin) {
          if (($h1 > $d_lever) && ($h2 < $d_coucher)) {
            echo "<p>Creneau jour ", $h1->format('d-m-Y H:i:s'), " - ", $h2->format('d-m-Y H:i:s'),"</p>";
            $creneaux[] = $h1;
          } else {
            echo "<p>Creneau nuit ", $h1->format('d-m-Y H:i:s'), " - ", $h2->format('d-m-Y H:i:s'),"</p>";
          }
          $h1 = $h1->add($interval);
          $h2 = $h2->add($interval);
        }
        ?>
      <p>Fin</p>
    </body>
  </html>
