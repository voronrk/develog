<?php

define('DEFAULT_LOG_FILENAME_LOCAL', 'report(' . date("Y-m-d H:i:s") . ').log'); // Default filename for local loging
define('DEFAULT_WRITE_MODE', 'w'); // Default write mode for log file

define('LOG_FILENAME_REMOTE', 'report(' . date("Y-m-d H:i:s") . ').log'); // Filename for remote loging
define('LOG_HANDLER', 'https://kibra24.ru/b24/logs/writelog.php'); // URL of remote log handler
