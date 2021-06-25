<?
require_once('tolog.php');

toLog::writeLog('test.log', 'a', $_SERVER);
toLog::sendLog($_SERVER['SERVER_ADDR'] . '.log', $_SERVER['SERVER_ADDR'], $_SERVER);
