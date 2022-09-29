<?
require_once('develog.php');

use Develog\Develog;

$log = new Develog();

$log->add($_SERVER, 'server-1');
$log->add($_SERVER, 'server-2');
$log->add($_SERVER, 'server-0');
$log->add($_SERVER);
$log->add($_SERVER, 'server-4');
$log->sendLog();

$log->sendLog($_SERVER, '========================================================');