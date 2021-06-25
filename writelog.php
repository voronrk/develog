<?php

/*
*    Handler for wave log on the my server
*/

$fileName = $_REQUEST['FILE_NAME'];
$portal = $_REQUEST['PORTAL'];
$dateTime = $_REQUEST['DATETIME'];
$data = $_REQUEST['DATA'];

$logfile=fopen ($fileName, "a");

fwrite($logfile, $portal . PHP_EOL);
fwrite($logfile, $dateTime . PHP_EOL);
fwrite($logfile, print_r($data,true) . PHP_EOL);

fclose($logfile);
?>