<?php

class Generic {

    public $test = Stavros;

    public static function httpError($header, $code, $message) {
        $error = new ErrorResponse;
        $error->sendStatusHeader = $header;
        $error->outLastError($code, $message);
    }

    /**
     * [writes log messages to a debug file]
     * @param  [string] $sfile (debug filename)
     * @param  [string] $str (log message)
     * @return []
     */
    public static function authLog($sfile, $str, $remoteIp = '', $dateTime = '') {
        file_put_contents($sfile, '[' . $remoteIp . '][' . $dateTime . '] ' . $str . "\r\n", FILE_APPEND);
    }

    public static function getDirectoryList ($directory) 
    {
        $results = array();
        $handler = opendir($directory);
        while ($file = readdir($handler)) {
            if ($file != "." && $file != "..") {
                $results[] = $file;
            }
        }
        closedir($handler);
        return $results;
    }

    public static function getGUID(){
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
        .substr($charid, 0, 8).$hyphen
        .substr($charid, 8, 4).$hyphen
        .substr($charid,12, 4).$hyphen
        .substr($charid,16, 4).$hyphen
        .substr($charid,20,12)
            .chr(125);// "}"
            return $uuid;
        }
    }

}

?>