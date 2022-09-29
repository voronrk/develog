<?

namespace Develog;

require_once('develogsettings.php');

/**
 * Class for save log
 * version 2.0
 */

class Develog {

    public $log;

    /**
     * Add data to log array. $key is key of array
     *
     * @param mixed $data
     * @param string|null $key
     * @return void
     */
    public function add(mixed $data, string $key = null)
    {
        if($key) {
            $this->log[$key] = $data;
        } else {
            $this->log[] = $data;
        };
    }

    /**
     * Write log to current place
     * 
     * Write log to current folder.
     * $fileName - name of file
     * $writeMode - 'a' for append, 'w' for rewrite
     * $arData - data for loging
     *
     * @param mixed $arData
     * @param string $fileName
     * @param string $writeMode
     * @return void
     */
    public function writeLog($arData = null, string $fileName = DEFAULT_LOG_FILENAME_LOCAL, $writeMode = DEFAULT_WRITE_MODE) 
    {
        if ($arData == null) {
            $arData = $this->log;
        };

        switch ($writeMode) {
            case 'a':
                $writeMode = 'a';
                break;
            case 'w': 
                $writeMode = 'w';
                break;
            default:
                $writeMode = DEFAULT_WRITE_MODE;
        };

        $logfile=fopen($fileName, $writeMode);
        if ($writeMode=='a') {
            fwrite($logfile, '-----------------------------------------------------------------------------' . PHP_EOL);
        };
        fwrite($logfile, date("Y-m-d H:i:s") . PHP_EOL);
        fwrite($logfile, print_r($arData, true) . PHP_EOL);
        fclose($logfile);
    }

    /**
     * Send log to remote server (the handler is writelog.php or other)
     *
     * @param mixed $arData
     * @param string $comment
     * @return void
     */
    public function sendLog($arData = null, string $comment = '') 
    {

        /*
        *   Send log to my server.
        *   $comment - comment about wrining block
        *   $arData - data for loging
        */

        if ($arData == null) {
            $arData = $this->log;
        };

        $url = LOG_HANDLER;
        $sPostFields = http_build_query([
            'FILE_NAME' => LOG_FILENAME_REMOTE,
            'COMMENT' => $comment,
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
