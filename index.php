<?php
    include("lib/ezsql/shared/ez_sql_core.php");
    include("lib/ezsql/mysql/ez_sql_mysql.php");
    
    include("functions.inc");

    $method = $_SERVER['REQUEST_METHOD'];
    $request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

    

    $db = new ezSQL_mysql('root','','rest_test','localhost');
    // needed to display greek characters correctly
    $db->query("SET NAMES utf8") ;
    $db->query("SET CHARACTER SET utf8") ;
    
    $collections = array();
    //for every collection mapped to the Database there has to be a line:
    //$collections[{collection_name}]={table_name}
    
    $collections["test"]="rest1";

    switch ($method) {
        case 'PUT':
            //rest_put($request);  
            break;
        case 'POST':
            //rest_post($request);  
            break;
        case 'GET':
            
            //dump_headers();
            $result = $db->get_results("SELECT * FROM ".$collections[$request[0]]." where id=".$request[1]);
            header('Content-type: application/json');
            echo json_encode($result);

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