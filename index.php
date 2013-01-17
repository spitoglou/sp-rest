<?php
    /**
    * RESTful Services Database Wrapper
    *  
    * @PHPVER   5
    *
    * @author   Stavros Pitoglou
    * @version  0.1
    * @date     15/01/2013
    * @license  GPL,LGPL
    */

    /**
    * Configuration File 
    */
    include("config.inc");

    /**
    * ez_sql db wrapper
    */
    include("lib/ezsql/shared/ez_sql_core.php");
    include("lib/ezsql/mysql/ez_sql_mysql.php");

    /**
    * Database connection
    */
    include("db_conn.inc");

    /**
    * Generic Functions
    */
    include("functions.inc");

    /**
    * Array2Xml
    */
    include("lib/class.array2xml2array.php");

    /**
    * Collections Definitions
    */
    include("collections.inc");


    $method = $_SERVER['REQUEST_METHOD'];
    $request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
    //print_r($_GET);

    //request validation and whitelisting
    $out=validate($request);
    if ($out<>"OK") {
        header("HTTP/1.0 400 Bad Request"); //400:Bad Request
        die($out);
    }


    //process get queries if present
    $query='';
    if (count($_GET)>0 and !($query=process_query($request[0],$_GET))) {
        header("HTTP/1.0 400 Bad Request"); //400:Bad Request
        die('Problem with the query (?...) part of the uri');
    } 
    //echo $query;

    //determine output format (json, xml) based on call's "Accept" header
    $h=getheaders('Accept');
    if (strpos($h,'json')>-1 or strpos($h,'*/*')>-1) {
        $output = 'json';
    } elseif   (strpos($h,'xml')>-1) {
        $output='xml';

    }   else {
        header("HTTP/1.0 400 Bad Request"); //400:Bad Request 
        die('Not supported method defined in call headers');
    }

    //main response procedures
    switch ($method) {
        case 'PUT':
            //not implemented yet  
            break;
        case 'POST':   //under construction 
            echo dump_headers();
            print_r($_POST);
            print_r($_SERVER);
              
            break;
        case 'GET':

            if (count($request)==1) {
                if ($query){
                    $result = $db->get_results("SELECT * FROM ".$collections[$request[0]]." where $query",ARRAY_A);
                    //$db->debug(); 
                }   else {
                    $result = $db->get_results("SELECT * FROM ".$collections[$request[0]],ARRAY_A); 
                }

            } else {
                $result = $db->get_results("SELECT * FROM ".$collections[$request[0]]." where id=".$request[1],ARRAY_A);   
            }

            if (count($result)>0) {


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
            } else {
                header("HTTP/1.0 404 Not Found"); //404:Not Found
                die(); 
            }

            break;
        case 'HEAD':
            //not implemented yet  
            break;
        case 'DELETE':
            if (count($request)>2) {
                header("HTTP/1.0 400 Bad Request"); //400:Bad Request
                die('Too many parameters in URI'); 
            }   elseif (count($request)==2) {
                if ($db->query("DELETE FROM ".$collections[$request[0]]." where id = $request[1]")) {
                    header("HTTP/1.0 202 Accepted"); //202 Accepted 
                } else {
                    header("HTTP/1.0 404 Not Found"); //404 Not Found 
                    die('Not Found');
                } 

            }   else {
                //echo dump_headers(); 
                //print_r($_REQUEST); 
                //print_r($_POST);
                $out_query=array(); 
                foreach ($_REQUEST as $name=>$value) {

                    if ($fields[$request[0]]<>'')   {
                        $qf=array();
                        $qf=explode(',',$fields[$request[0]]);
                        foreach ($qf as $field_name) {
                            if ($name==$field_name and mysql_escape_string($value)==$value) {
                                $out_query[]=" $name = '$value'";
                            }
                        }
                    }
                } 
                $query= implode(" and ",$out_query);
                //echo $query;
                if ($db->query("DELETE FROM ".$collections[$request[0]]." where $query")) {
                    header("HTTP/1.0 202 Accepted"); //202 Accepted 
                } else {
                    header("HTTP/1.0 404 Not Found"); //404 Not Found 
                    die('Not Found');
                } 
            }
            break;
        case 'OPTIONS':
            //not implemented yet    
            break;
        default:
            //not implemented yet  
            break;
    }


?>