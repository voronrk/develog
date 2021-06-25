<?

/*
*   Class for save log
*/

class toLog {

    public static function writeLog($fileName, $writeMode, $arData) {

        /*
        *   Write log to current folder.
        *   $fileName - name of file
        *   $writeMode - 'a' for append, 'w' for rewrite
        *   $arData - data for loging
        */

        $logfile=fopen($fileName, $writeMode);
        if ($writeMode=='a') {
            fwrite($logfile, '-----------------------------------------------------------------------------' . PHP_EOL);
        };
        fwrite($logfile, date("Y-m-d H:i:s") . PHP_EOL);
        fwrite($logfile, print_r($arData, true) . PHP_EOL);
        fclose($logfile);
    }

    public static function sendLog($fileName, $portal, $arData) {

        /*
        *   Send log to my server.
        *   $fileName - name of file
        *   $portal - address of client's portal
        *   $arData - data for loging
        */

        $url = 'https://kibra24.ru/b24/logs/writelog.php';
        $sPostFields = http_build_query([
            'FILE_NAME' => $fileName,
            'PORTAL' => $portal,
            'DATETIME' => date("Y-m-d H:i:s"),
            'DATA' => $arData
        ]);

        $obCurl = curl_init();
        curl_setopt($obCurl, CURLOPT_URL, $url);
        curl_setopt($obCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($obCurl, CURLOPT_POSTREDIR, 10);
        curl_setopt($obCurl, CURLOPT_POST, true);
		curl_setopt($obCurl, CURLOPT_POSTFIELDS, $sPostFields);
        $out = curl_exec($obCurl);
        curl_close($obCurl);
    }
};
