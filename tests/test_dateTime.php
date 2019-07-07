<!DOCTYPE html>
  <html lang="fr">
    <head>
      <meta charset="utf-8" />
      <title>Test DateTime PHP</title>
    </head>
    <body>
      <?php
        date_default_timezone_set("Pacific/Nauru");
        $timeZone = new DateTimeZone('Europe/Paris');
        ?>
      <h1>Maintenant</h1>
      <?php
        $maintenant = new DateTime("now", $timeZone);
        echo $maintenant->format('Y-m-d H:i:s');
      ?>
      <h1>Aujourdhui</h1>
      <?php
        $j = new DateTime("now", $timeZone);
        $j->setTime(0, 0, 0);
        echo $j->format('Y-m-d H:i:s');
        ?>
      <h1>Heure dans la journée</h1>
      <?php
        $j = new DateTime("now", $timeZone);
        $j->setTime(0, 0, 0);
        echo $j->format('d-m-Y H:i:s');
        
        
        $h1 = new DateInterval('PT7H30M00S');
        $j1 = new DateTime($j->format('Y-m-d'), $timeZone);
        $j1 = $j1->add($h1);
        
        echo '<p>' , $j->format('Y-m-d H:i:s'),  '</p>';
        echo $j1->format('Y-m-d H:i:s');
      
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
        
        $t_lever = date_sunrise(time(), SUNFUNCS_RET_TIMESTAMP, 48.35, -4.68);
        $d_lever = new DateTime("now", $timeZone);
        $d_lever->setTimestamp($t_lever);
        echo "<p>Lever : " , $d_lever->format('d-m-Y H:i:s'), "</p>";
        
        $t_coucher = date_sunset(time(), SUNFUNCS_RET_TIMESTAMP, 48.35, -4.68);
        $d_coucher = new DateTime("now", $timeZone);
        $d_coucher->setTimestamp($t_coucher);
        echo "<p>coucher : " , $d_coucher->format('d-m-Y H:i:s'), "</p>";
        
        $h1 = $d->add($debut);
        $h2 = $d->add($debut);
        $h2= $h2->add($interval);
        $creneaux = array();
        while ($h2 <= $dfin) {
          if (($h1 > $d_lever) && ($h2 < $d_coucher)) {
            echo "<p>Creneau jour ", $h1->format('Y-m-d H:i:s'), " - ", $h2->format('Y-m-d H:i:s'),"</p>";
            $creneaux[] = $h1;
          } else {
            echo "<p>Creneau nuit ", $h1->format('Y-m-d H:i:s'), " - ", $h2->format('Y-m-d H:i:s'),"</p>";
          }
          $h1 = $h1->add($interval);
          $h2 = $h2->add($interval);
        }
        ?>
      <p>Fin</p>
    </body>
  </html>
