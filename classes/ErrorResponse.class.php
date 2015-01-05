<?php

class ErrorResponse extends Response 
{
    public $errorCode;
    public $errorDescription;

    public function outLastError($code,$descr) {
        
        global $db;
        $this->error=true;
        $this->responseArray=array();
        $this->responseArray['err_status_code']=$code;
        if ($descr) {
            $this->responseArray['error_descr']=$descr;
        } else {
            $this->responseArray['error_descr']=$db->last_error;
            $this->responseArray['error_query']=$db->last_query;
        }
        header('X-Error-Description:'. $this->responseArray['error_descr']);
        $this->finalSend('error');
    }
}


?>