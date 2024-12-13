<?php
set_include_path('./../');

require_once 'php/metier/calendrier.php';


$t1 = Instant::micro();
usleep(100);
$t2 = Instant::micro();
$diff = $t2 - $t1;

print(PHP_EOL . "t1: " . number_format($t1, 6));
print(PHP_EOL . "t2: " . number_format($t2, 6));
print(PHP_EOL . "t2: " . number_format($diff, 6));

$i1 = new Instant();
print(PHP_EOL . "micro sql:" . $i1->micro_sql() . PHP_EOL);

// ============================================================================
?>
