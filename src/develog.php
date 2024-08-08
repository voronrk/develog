<?
namespace Develog;

use ZipArchive;
use DateInterval;

include('develogsettings.php');

/**
 * Class for save log
 * version 4.0
 */

class Develog {

    /**
     * Add data to log array. $key is key of array
     *
     * @param mixed $data
     * @param string|null $key
     * @return void
     */
    public function add($data, string $key = null, $isArray = false)
    {
        if($key) {
            if($isArray) {
                $this->log[$key][] = $data;
            } else {
                $this->log[$key] = $data;
            }            
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

        if($this->format == 'JSON') {
            file_put_contents($this->logFileName, json_encode($arData, JSON_UNESCAPED_UNICODE));
        } else {
            $logfile = fopen($this->logFileName, $this->writeMode);
            if ($this->writeMode == 'a') {
                fwrite($logfile, '-----------------------------------------------------------------------------' . PHP_EOL);
            };
            fwrite($logfile, date("Y-m-d H:i:s") . PHP_EOL);
            fwrite($logfile, print_r($arData, true) . PHP_EOL);
            fclose($logfile);
        }
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

    private function logsPack()
    {
        $zipFilePath = $this->arcLogDir . date('Ymd', strtotime('yesterday')) . '.zip';

        $zip = new ZipArchive();
        $zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $files = scandir($this->logDir);
        foreach ($files as $file) {
            $filePath = $this->logDir . $file;
            if (is_file($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === EXTENSIONS[$this->format]) {
                $zip->addFile($filePath, $file);
            }
        }

        if ($zip->close()) {
            foreach ($files as $file) {
                $filePath = $this->logDir . $file;
                if (is_file($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === EXTENSIONS[$this->format]) {
                    unlink($filePath);
                }
            }
            return true;
        } else {
            return false;
        }
    }

    private function checkFirstRun()
    {
        $lastRunFile = 'last_run_date.txt';
        $currentDate = date('Y-m-d');

        if (file_exists($lastRunFile)) {
            $lastRunDate = file_get_contents($lastRunFile);
        } else {
            $lastRunDate = '';
        }

        if ($currentDate !== $lastRunDate) {
            $this->logsPack();
			$this->deletingArchiveFilename = $this->arcLogDir . date('Ymd', date_create()->sub(new DateInterval("P" . LOG_RETENTION_PERIOD_DAYS . "D"))->getTimestamp()) . '.zip';
			if(file_exists($this->deletingArchiveFilename)) {
                unlink($this->deletingArchiveFilename);
            }
            file_put_contents($lastRunFile, $currentDate);
        }
    }
    
    /**
     * Constructor
     * 
     * $fileName - name of file
     * $format - 'JSON' for json, 'LOG' for txt
     * $writeMode - 'a' for append, 'w' for rewrite
     *
     * @param string $fileName
     * @param string $format
     * @param string $writeMode
     * @return void
     */
    public function __construct(string $fileName = DEFAULT_LOG_FILENAME_LOCAL, string $format = DEFAULT_FORMAT, string $writeMode = DEFAULT_WRITE_MODE, string $logDir = DEFAULT_LOG_DIR, string $arcLogDir = DEFAULT_ARCLOG_DIR)
    {

        $this->log = [];
		$this->logDir = $logDir . DIRECTORY_SEPARATOR;
		$this->arcLogDir = $arcLogDir . DIRECTORY_SEPARATOR;

        switch (strtoupper($format)) {
            case 'JSON':
                $this->format = 'JSON';
                break;
            case 'LOG': 
                $this->format = 'LOG';
                break;
            default:
                $this->format = DEFAULT_FORMAT;
        };

        $this->logFileName = $this->logDir . $fileName . '.' . EXTENSIONS[$this->format];

        if($this->format == 'JSON') {
            $this->writeMode = 'w';
        } else {
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
		
		$this->checkFirstRun();
    }
};
