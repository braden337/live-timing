<?php

header('Access-Control-Allow-Origin: https://deaths.surge.sh');
header('Content-Type: application/json');

$deaths = array_map('str_getcsv', file('deaths.csv'));

array_walk($deaths, function(&$a, $i) use ($deaths) {
    $date = date_create($a[2]);
    $a = array_combine($deaths[0], $a);
    if ($date) $a['date'] = $date->format('c');
});

array_shift($deaths);

echo json_encode($deaths);
