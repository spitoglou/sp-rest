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
            if (count($request)<>2) {
                header("HTTP/1.0 400 Bad Request"); //400:Bad Request
                die('Wrong number of parameters in URI(must be 2)'); 
            }   else  {
                $out_query=array(); 
                //echo dump_headers();
                //print_r($_PUT);
                //print_r($_SERVER);
                parse_str(file_get_contents("php://input"),$put_vars);
                //print_r($put_vars);
                foreach ($put_vars as $name=>$value) {

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
                $query= implode(", ",$out_query);
                //echo $query;
                if ($db->query("UPDATE ".$collections[$request[0]]." SET $query WHERE ".$pk[$request[0]]."=".$request[1])) {
                    header("HTTP/1.0 202 Accepted"); //202 Accepted 
                } else {
                    header("HTTP/1.0 404 Not Found"); //404 Not Found 
                    die('Not Found');
                } 

            }   
            break;
        case 'POST':   //under construction 
            //echo dump_headers();
            //print_r($_POST);
            //print_r($_SERVER);
            if (count($request)>1) {
                header("HTTP/1.0 400 Bad Request"); //400:Bad Request
                die('Too many parameters in URI'); 
            }   else {
                $out_query1=array(); 
                $out_query2=array();
                foreach ($_POST as $name=>$value) {

                    if ($fields[$request[0]]<>'')   {
                        $qf=array();
                        $qf=explode(',',$fields[$request[0]]);
                        foreach ($qf as $field_name) {
                            if ($name==$field_name and mysql_escape_string($value)==$value) {
                                $out_query1[]="$name";
                                $out_query2[]="'$value'"; 
                            }
                        }
                    }
                } 
                $query= "(".implode(",",$out_query1).") values (".implode(",",$out_query2).")";
                //echo $query;
                if (count($out_query1)>0 and $db->query("insert into ".$collections[$request[0]]." $query")) {
                    header("HTTP/1.0 201 Created"); //201 Created 
                } else {
                    header("HTTP/1.0 500 Internal Server Error"); //500 Internal Server Error 
                    die('Cound not create resource');
                } 
            }
            break;
        case 'GET':

            if (count($request)==1) {
                if ($query){
                    $send_to_db="SELECT ".$fields[$request[0]];
                    $send_to_db.=" FROM ".$collections[$request[0]];
                    $send_to_db .= ' WHERE 1=1 ';
                    if ($query['where_clause']) {
                        $send_to_db .= " AND ".$query['where_clause'];
                    }
                    if ($query['order_clause']) {
                        $send_to_db .= " order by ".$query['order_clause'];
                    }  
                    if ($query['limit']) {
                        switch ($config['dbtype']) {
                            case 'mysql':
                                $send_to_db .= " limit ";
                                if ($query['offset']) {
                                    $send_to_db .= $query['offset'].",";
                                }
                                $send_to_db .= $query['limit'];           
                                break;
                            
                            case 'orcl':
                                $send_to_db = "SELECT a.*,ROWNUM rnum FROM (".$send_to_db.") a where ROWNUM<=".($query['offset']+$query['limit']);
                                $send_to_db= "SELECT * FROM (".$send_to_db.") WHERE rnum>".(0 +$query['offset']);
                                break;
                        }
                    }
                    $result = $db->get_results($send_to_db,ARRAY_A);
                    //$db->debug(); 
                }   else {
                    $result = $db->get_results("SELECT ".$fields[$request[0]]." FROM ".$collections[$request[0]],ARRAY_A);
                    //$db->debug(); 
                }

            } else {
                $result = $db->get_results("SELECT ".$fields[$request[0]]." FROM ".$collections[$request[0]]." where ".$pk[$request[0]]."=".$request[1],ARRAY_A);   
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
                if ($db->query("DELETE FROM ".$collections[$request[0]]." where ".$pk[$request[0]]."= $request[1]")) {
                    header("HTTP/1.0 202 Accepted"); //202 Accepted 
                } else {
                    header("HTTP/1.0 404 Not Found"); //404 Not Found 
                    die('Not Found');
                } 

            }   else {
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