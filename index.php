<?php
    /**
    * RESTful Services Database Wrapper
    *  
    * @PHPVER    5
    *
    * @author    Stavros Pitoglou
    * @ver        0001.0001
    * @date    15/01/2013
    */


    include("lib/ezsql/shared/ez_sql_core.php");
    include("lib/ezsql/mysql/ez_sql_mysql.php");

    include("functions.inc");

    include("lib/class.array2xml2array.php");

    $method = $_SERVER['REQUEST_METHOD'];
    $request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

    
    //determine output format (json, xml) based on call's "Accept" header
    $h=getallheaders('Accept');
    //echo $h;//test
    if (strpos($h,'json')>-1 or strpos($h,'*/*')>-1) {
        $output = 'json';
    } elseif   (strpos($h,'xml')>-1) {
        $output='xml';

    }   else {
        header("Status: 400"); //400:Bad Request
        die('Not supported method defined in call headers');
    }

    //connection to database
    $db = new ezSQL_mysql('root','','rest_test','localhost');
    // needed to display greek characters correctly
    $db->query("SET NAMES utf8") ;
    $db->query("SET CHARACTER SET utf8") ;

    
    //for every collection mapped to the Database there has to be a line:
    //$collections[{collection_name}]={table_name}
    $collections = array();  
    $collections["test"]="rest1";

    switch ($method) {
        case 'PUT':
            //rest_put($request);  
            break;
        case 'POST':
            //rest_post($request);  
            break;
        case 'GET':

        if (count($request)==1) {
            $result = $db->get_results("SELECT * FROM ".$collections[$request[0]],ARRAY_A);
        } else {
            $result = $db->get_results("SELECT * FROM ".$collections[$request[0]]." where id=".$request[1],ARRAY_A);   
        }

        switch ($output) {
            case 'json':
                header('Content-type: application/json');
                echo json_encode($result);
                break;

            case 'xml':
                header('Content-type: text/xml');    
                $array2XML = new CArray2xml2array();       
                $array2XML->setArray($result);
                echo $array2XML->saveArray('results');
                break;

        }

        break;
        case 'HEAD':
            //rest_head($request);  
            break;
        case 'DELETE':
            //rest_delete($request);  
            break;
        case 'OPTIONS':
            //rest_options($request);    
            break;
        default:
            //rest_error($request);  
            break;
    }


?>