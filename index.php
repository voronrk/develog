<?
require_once('tolog.php');

deveLog::writeLog('test.log', 'a', $_SERVER);
deveLog::sendLog($_SERVER['SERVER_ADDR'] . '.log', $_SERVER['SERVER_ADDR'], $_SERVER);
