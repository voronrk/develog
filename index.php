<?
include(__DIR__ . '/src/develog.php');

use Develog\Develog;

function debug($data)
{
    echo "<pre>";
    echo print_r($data,true);
    echo "</pre>";
}

$log = new Develog();
debug($log);

$log->add($_SERVER, 'server-1');
$log->add($_SERVER, 'server-2');
$log->add($_SERVER, 'server-0');
$log->add($_SERVER);
$log->add($_SERVER, 'server-4');
$log->writeLog();

// $log->sendLog($_SERVER, '========================================================');