<?
require_once('src/develog.php');

deveLog::writeLog($_SERVER);
deveLog::sendLog($_SERVER);
