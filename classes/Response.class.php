<?php

class Response {

    public $output;
    public $responseArray;
    public $sendStatusHeader;
    public $error = false;
    public $cache = '';
    public $responseText;

    function __construct() {
        //global $request;
        //if ($request) 
        $this->defineOutput($_SERVER['HTTP_ACCEPT']);
    }

    public function defineOutput($h) {
        $reply = true;
        if (strpos($h, 'json') > -1) {
            $this->output = 'json';
        } elseif (strpos($h, 'cs+xml') > -1) {
            $this->output = 'xml';
        } elseif (strpos($h, 'mirror+xml') > -1) {
            $this->output = 'mirror';
        } elseif (strpos($h, 'mirror+jsn') > -1) {
            $this->output = 'mirrorjson';
        }else {
            $reply = false;
        }
        return $reply;
    }

    public function finalSend($root='') {
        global $config;
        global $request;
        if ($config['log']) {
            $log_dt = new DateTime();
            if (!$this->error) {
                Generic::authLog('logs/requests.log', 'Response : (' . $this->sendStatusHeader . ') Item Count : ' . count($this->responseArray), $_SERVER['REMOTE_ADDR'], date_format($log_dt, 'dmYHis'));
            } else {
                Generic::authLog('logs/error_responses.log', 'Response : (' . $this->sendStatusHeader . ') ' . $this->responseArray['err_status_code'] . ' , ' . $this->responseArray['error_descr'], $_SERVER['REMOTE_ADDR'], date_format($log_dt, 'dmYHis'));
            }
        }

        if ($this->sendStatusHeader) {
            header($this->sendStatusHeader);
        }
        if (!$this->error) {
            if ($request->haltTime) {
                sleep($request->haltTime);
            }
            header('X-Number-Of-Results:' . count($this->responseArray));
        }
        header('X-Powered-By: Computer Solutions Web Services');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Expose-Headers: X-Number-Of-Results, X-Powered-By, X-Error-Description');
        header('Cache-Control: max-age=' . ($this->cache ? $this->cache : '30') . ',s-maxage=' . ($this->cache ? $this->cache : '30') . ' ,must-revalidate');
        switch ($this->output) {
            case 'json':
            header('Content-type: application/json');

            echo json_encode($this->responseArray);
            break;

            case 'xml':
            header('Content-type: text/xml');
            $array2XML = new CArray2xml2array();
            $array2XML->setArray($this->responseArray);
            if ($root) {
                echo $array2XML->saveArray($root);
            } else {
                echo $array2XML->saveArray('results');
            }
            break;

            case 'mirror':
            header('Content-type: text/xml');

            echo $this->responseText;
            break;

            case 'mirrorjson':
            header('Content-type: application/json');

            echo $this->responseText;
            break;

            case 'mirrorplain':

            echo $this->responseText;
            break;

            default:
                //var_dump($this->response_array);
            echo $this->array2table($this->responseArray);
            break;
        }
        die();
    }

    private function array2table($array, $recursive = false, $null = '&nbsp;') {
        // Sanity check
        if (empty($array) || !is_array($array)) {
            return 'empty';
        }
        if (!isset($array[0]) || !is_array($array[0])) {
            $array = array($array);
        }

        // Start the table
        $table = "<table border=1>\n";

        // The header
        $table .= "\t<tr>";
        // Take the keys from the first row as the headings
        foreach (array_keys($array[0]) as $heading) {
            $table .= '<th>' . $heading . '</th>';
        }
        $table .= "</tr>\n";

        // The body
        foreach ($array as $row) {
            $table .= "\t<tr>";
            foreach ($row as $cell) {
                $table .= '<td>';

                // Cast objects
                if (is_object($cell)) {
                    $cell = (array) $cell;
                }

                if ($recursive === true && is_array($cell) && !empty($cell)) {
                    // Recursive mode
                    $table .= "\n" . array2table($cell, true, true) . "\n";
                } else {
                    $table .= (strlen($cell) > 0) ?
                    htmlspecialchars((string) $cell) :
                    $null;
                }

                $table .= '</td>';
            }

            $table .= "</tr>\n";
        }

        $table .= '</table>';
        return $table;
    }

}

?>