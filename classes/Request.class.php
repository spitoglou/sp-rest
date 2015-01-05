<?php

/**
* Request Class
*/
class Request
{
    public $method;
    public $path;
    public $headers;
    public $query;
    public $collection='';
    public $requestBody;
    public $validationResult;
    public $haltTime;

    function __construct()
    {
        global $collections;
        global $allowed_methods;
        global $allowed_ips;
        global $config;
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->haltTime=$_GET['sleep'];
        $this->path = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
        $this->headers=$this->getHeaders();
        $this->requestBody=file_get_contents("php://input");
        $this->collection=$collections[$this->path[0]];

        if ($config['log']) {
            $log_dt= new DateTime();
            Generic::authLog('logs/requests.log','Request : '.$_SERVER['PATH_INFO'].'?'.urldecode($_SERVER['QUERY_STRING']),$_SERVER['REMOTE_ADDR'],date_format($log_dt,'dmYHis'));
        }
        
        //collection exists & sql injection check
        if (($this->validationResult=$this->validate($this->path))<>'OK') {
            Generic::httpError('HTTP/1.1 400 Bad Request','400',$this->validationResult);
        }
        
        //Method filtering
        $a=$allowed_methods[$this->path[0]];
        if ($a!=='ALL' and strpos($a, $this->method)===false) {
            Generic::httpError('HTTP/1.1 403 Forbidden','403','Method '.$this->method.' not allowed for this collection');
        }
        
        //IP filtering 
        if ($ips=$allowed_ips[$this->path[0]]) {
            $allowed=false;
            foreach ($ips as $key => $value) {
                if (!(stripos($_SERVER['REMOTE_ADDR'],$value)===false)) {
                    $allowed=true;
                }
            }
            if (!$allowed) {
                Generic::httpError('HTTP/1.1 403 Forbidden','403',"This Collection is Not Accessible from IP address : ".$_SERVER['REMOTE_ADDR']);
            }
        }

        if ($this->collection!=='custom' and $this->collection!=='orcl_func') {
            if (count($_GET)>0 and !($this->query=$this->processQuery($this->path[0],$_GET))) {
                Generic::httpError('HTTP/1.1 400 Bad Request','400','Problem with the query (?...) part of the uri');
            } 
        }
        
    }

    private function getHeaders($q='') 
    {
        foreach($_SERVER as $name => $value)
        {
            if(substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            } else {
                $headers[$name]=$value;
            }
        }
        if (!$q) {
            return $headers;
        } else {
            return $headers[$q];
        }
    }

    private function validate($args) {
        global $collections;
        /*foreach ($args as $name=>$value) {
            if (($a=filter_var($value,FILTER_SANITIZE_FULL_SPECIAL_CHARS))<>$value){ 
                return $a." : Are you trying to mess with me by injecting?";
            }

        }*/
        if ($collections[$args[0]]=='') {
            return  $collections[$args[0]]." $args[0] not a valid collection";
        }

        return "OK";
    }

    public function processQuery($collection,$q) {

        global $fields;
        global $config;
        $out_query=array(); 
        foreach ($q as $name=>$value) {
            $name=strtolower($name);
            switch ($name) {
                case 'offset':
                $offset=$value;
                break;

                case 'limit':
                $limit=$value;
                break;

                case 'order':
                $order=$value;
                break;

                case 'custom_query':
                $customQuery=$value;
                break;

                default:
                if ($fields[$collection]<>'')   {
                    $qf=array();
                    $qf=explode(',',$fields[$collection]);
                    foreach ($qf as $field_name) {
                        $field_details=array();
                        $field_details=explode('|',$field_name);
                        if ($name==strtolower($field_details[0]) and mysql_escape_string($value)==$value) {
                            if ($field_details[1]=='NUM') {
                                $out_query[]=" $field_details[0] = $value";
                            } else {
                                $out_query[]=" $field_details[0] like '%$value%'";
                            }

                        }
                    }
                }
                break;
            }

        } 

        $output=array();
        if ($out_query) $output['where_clause']= implode(" and ",$out_query);
        if ($limit) $output['limit']=$limit;
        if ($offset) $output['offset']=$offset;
        if ($order) $output['order_clause']=$order;
        if ($customQuery) $output['customQuery']=$customQuery;
        return $output;
    }
}

?>