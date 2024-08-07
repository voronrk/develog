<?php

define('DEFAULT_LOG_DIR', 'logs');
define('DEFAULT_ARCLOG_DIR', 'arclogs');
define('DEFAULT_LOG_FILENAME_LOCAL', date("Y-m-d_H-i-s")); // Default filename for local loging
define('DEFAULT_WRITE_MODE', 'w'); // Default write mode for log file
define('DEFAULT_FORMAT', 'JSON'); // Default format of log file

define('EXTENSIONS', [
    'JSON' => 'json',
    'LOG' => 'log',
]);

define('LOG_FILENAME_REMOTE', date("Y-m-d H:i:s") . '.log'); // Filename for remote loging
define('LOG_HANDLER', 'https://kibra24.ru/b24/logs/writelog.php'); // URL of remote log handler
