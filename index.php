<?
require_once('deveLog.php');

deveLog::writeLog('test.log', 'a', $_SERVER);
deveLog::sendLog('https://kibra24.ru/b24/logs/writelog.php', $_SERVER['SERVER_ADDR'] . '.log', $_SERVER['SERVER_ADDR'], $_SERVER);
