<?

namespace Develog;

require_once('develogsettings.php');

/**
 * Class for save log
 * version 3.0
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
     * $arData - data for loging
     *
     * @param mixed $arData
     * @return void
     */
    public function writeLog($arData = null) 
    {
        if ($arData == null) {
            $arData = $this->log;
        };

        $logfile = fopen($this->logFileName, $this->writeMode);
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
    
    /**
     * Constructor
     * 
     * $fileName - name of file
     * $writeMode - 'a' for append, 'w' for rewrite
     *
     * @param string $fileName
     * @param [type] $writeMode
     * @return void
     */
    public function __construct(string $fileName = DEFAULT_LOG_FILENAME_LOCAL, string $writeMode = DEFAULT_WRITE_MODE)
    {
        $this->logFileName = $fileName;

        switch ($writeMode) {
            case 'a':
                $this->writeMode = 'a';
                break;
            case 'w': 
                $this->writeMode = 'w';
                break;
            default:
                $this->writeMode = DEFAULT_WRITE_MODE;
        };
    }
};
