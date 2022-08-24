<?
require_once('develog.php');

deveLog::writeLog($_SERVER, 'local.log', 'a');
deveLog::sendLog($_SERVER, 'This is log');
