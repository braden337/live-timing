<?php

header('Access-Control-Allow-Origin: /');
header('Content-Type: application/xml');


$xml = file_get_contents('https://live.amasupercross.com/xml/sx/RaceResultsWeb.xml');

echo $xml;
