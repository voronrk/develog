<?
require_once('develog.php');

deveLog::writeLog($_SERVER);
deveLog::sendLog($_SERVER);
