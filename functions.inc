<?php

    /**
    * gets the http headers of a client request (Idea and core code: http://www.binarytides.com/get-http-request-headers-in-php/)
    * 
    * @param mixed $q Specific http header
    * @return array 
    */

    function getheaders($q) 
    {
        foreach($_SERVER as $name => $value)
        {
            if(substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        if (!$q) {
            return $headers;
        } else {
            return $headers[$q];
        }
    }

    /**
    * echoes all http headers
    * 
    */
    function dump_headers() {
        foreach (getheaders() as $name => $value) 
        {
            echo "$name: $value\n";

        }
    }


    function validate($args) {
        global $collections;
        foreach ($args as $name=>$value) {
            if (mysql_escape_string($value)<>$value){ 
                return mysql_escape_string($value)."\n$value\nYou are trying to mess with me by injecting sql mothafucka?";
            }
        }
        /*if (!array_search($args[0],$collections))   {
        return "$args[0] not a valid collection";
    } */
    if ($collections[$args[0]]=='') {
        return  $collections[$args[0]]." $args[0] not a valid collection";
    }
    if (count($args)>1 and !is_numeric($args[1])) {
        return "$args[1] not a valid index";
    }
    return "OK";
}

function process_query($collection,$q) {

    global $fields;
    global $config;
    $out_query=array(); 
    foreach ($q as $name=>$value) {
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

            default:
            if ($fields[$collection]<>'')   {
                $qf=array();
                $qf=explode(',',$fields[$collection]);
                //print_r($qf);
                foreach ($qf as $field_name) {
                    if ($name==$field_name and mysql_escape_string($value)==$value) {
                        if (is_numeric($value)) {
                            $out_query[]=" $name = $value";
                        } else {
                            $out_query[]=" $name like '$value'";
                        }

                    }
                }
            }
            break;
        }

    } 

    $output=array();
    $output['where_clause']= implode(" and ",$out_query);
    $output['limit']=$limit;
    $output['offset']=$offset;
    $output['order_clause']=$order;
    

    return $output;
}

function out_last_error($code,$descr='') {
    global $output;
    global $db;

    $error_array=array();
    $error_array['err_status_code']=$code;
    if ($descr) {
        $error_array['error_descr']=$descr;
    } else {
        $error_array['error_descr']=$db->last_error;
        $error_array['error_query']=$db->last_query;
    }

    switch ($output) {
        case 'json':
        header('Content-type: application/json');
        echo json_encode($error_array);
        break;

        case 'xml':
        header('Content-type: text/xml');    
        $array2XML = new CArray2xml2array();       
        $array2XML->setArray($error_array);
        echo $array2XML->saveArray('error');
        break;
    }

}

?>