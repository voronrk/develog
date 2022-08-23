<?php

/*
*    Handler for save log on the my server.
*/

$fileName = $_REQUEST['FILE_NAME'];
$comment = $_REQUEST['COMMENT'];
$dateTime = $_REQUEST['DATETIME'];
$data = $_REQUEST['DATA'];

$logfile=fopen ($fileName, "a");

fwrite($logfile, $comment . PHP_EOL);
fwrite($logfile, $dateTime . PHP_EOL);
fwrite($logfile, print_r($data,true) . PHP_EOL);

fclose($logfile);

