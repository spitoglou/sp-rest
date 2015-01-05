<?php
$response=new Response;

switch ($request->method) {

//get method implementation
    case 'GET':

    $p=$request->path;
    if (count($p)>2) {
        Generic::httpError('HTTP/1.1 400 Bad Request','400','Too many (>2) path parameters');
    }
    
    $indicators=array('/\|NUM/','/\|DATE/');
    $fieldset= strtolower(preg_replace($indicators, array('',''), $fields[$p[0]]));
    if (count($p)==1) {
        $q=$request->query;
        if ($q) {
            $send_to_db ="SELECT ".$fieldset;
            $send_to_db .=" FROM ".$collections[$p[0]];
            $send_to_db .= ' WHERE 1=1 ';
            if ($q['where_clause']) {
                $send_to_db .= " AND ".$q['where_clause'];
            }
            if ($q['customQuery']) {
                $send_to_db .= " AND ".$q['customQuery'];
            } 
            if ($q['order_clause']) {
                $send_to_db .= " order by ".$q['order_clause'];
            }  
            if ($q['limit']) {
                switch ($dbtype[$request->path[0]]) {
                    case 'mysql':
                    $send_to_db .= " limit ";
                    if ($q['offset']) {
                        $send_to_db .= $q['offset'].",";
                    }
                    $send_to_db .= $q['limit'];           
                    break;

                    case 'orcl':
                    $send_to_db = "SELECT a.*,ROWNUM rnum FROM (".$send_to_db.") a where ROWNUM<=".($q['offset']+$q['limit']);
                    $send_to_db= "SELECT * FROM (".$send_to_db.") WHERE rnum>".(0 +$q['offset']);
                    break;
                }
            }
            $result = $db->get_results($send_to_db,ARRAY_A);
                //$db->debug(); 
        }   else {
            $result = $db->get_results("SELECT ".$fieldset." FROM ".$collections[$p[0]],ARRAY_A);
                //$db->debug(); 
        }
    } else {
        $result = $db->get_results("SELECT ".$fieldset." FROM ".$collections[$p[0]]." where ".$pk[$p[0]]."= '".$p[1]."'",ARRAY_A);
            //$db->debug();   
    }

    if (count($result)>0) {
        foreach ($result as $key => $value) {
            $value1= array_change_key_case($value, CASE_LOWER);
            $result[$key]=$value1;
        }
        //print_r($result);
        $response->responseArray=$result;
        $response->finalSend();

    } else {
        Generic::httpError('HTTP/1.1 404 Not Found','404','Empty Resultset');
    }

    break;

    //put method implementation 
    case 'PUT':

    $p=$request->path;
    if (count($p)<>2) {
        Generic::httpError('HTTP/1.1 400 Bad Request','400','Wrong number of parameters in URI(must be 2)');
    }   else  {
        $out_query=array();
        parse_str($request->requestBody,$put_vars);
        foreach ($put_vars as $name=>$value) {
            if ($fields[$p[0]]<>'')   {
                $qf=array();
                $qf=explode(',',$fields[$p[0]]);
                foreach ($qf as $field_name) {
                    if ($name==$field_name and mysql_escape_string($value)==$value) {
                        $out_query[]=" $name = '$value'";
                    }
                }
            }
        } 
        $query= implode(", ",$out_query);
        if ($db->query("UPDATE ".$collections[$p[0]]." SET $query WHERE ".$pk[$p[0]]."= '".$p[1]."'")) {
            $response->sendStatusHeader='HTTP/1.1 202 Accepted';
            $response->responseArray=array('status' => 'set', 'path' => '/'.$p[0].'/'.$pk[$p[0]], 'changes' => $query);
            $response->finalSend();
        } else {
            Generic::httpError('HTTP/1.1 404 Not Found','404','');
        } 

    }   
    break;

    //post method implementation
    case 'POST':
    $p=$request->path;
    if (count($p)>1) {
        Generic::httpError('HTTP/1.1 400 Bad Request','400','Wrong number of parameters in URI');
    }   else {
        $out_query1=array(); 
        $out_query2=array();
        foreach ($_POST as $name=>$value) {
            if ($fields[$p[0]]<>'') {
                $qf=array();
                $qf=explode(',',$fields[$p[0]]);
                foreach ($qf as $field_name) {
                    if ($name==$field_name and mysql_escape_string($value)==$value) {
                        $out_query1[]="$name";
                        $out_query2[]="'$value'"; 
                    }
                }
            }
        } 
        $query= "(".implode(",", $out_query1).") values (".implode(",", $out_query2).")";
            //echo $query;
        if (count($out_query1)>0 and $db->query("insert into ".$collections[$p[0]]." $query")) {
            $response->sendStatusHeader='HTTP/1.1 201 Created';
            $response->responseArray=array('status' => 'created');
            $response->finalSend();

        } else {
            Generic::httpError('HTTP/1.1 500 Internal Server Error', '500', '');
        } 
    }
    break;

    //delete method implementation
    case 'DELETE':
    $p=$request->path;
    if (count($p)>2) {
        Generic::httpError('HTTP/1.1 400 Bad Request','400','Wrong number of parameters in URI');
    } elseif (count($p)==2) {
        $db->show_errors();
        if ($db->query("DELETE FROM ".$collections[$p[0]]." where ".$pk[$p[0]]."= '$p[1]'")) {
            $response->sendStatusHeader='HTTP/1.1 202 Accepted';
            $response->responseArray=array('status' => 'deleted', 'destroyed_member' => $pk[$p[0]].'/'.$p[1]);
            $response->finalSend();
        } else {
            Generic::httpError('HTTP/1.1 404 Not Found','404','');
        } 

    }   else {
        $out_query=array(); 
        foreach ($_REQUEST as $name=>$value) {

            if ($fields[$p[0]]<>'') {
                $qf=array();
                $qf=explode(',',$fields[$p[0]]);
                foreach ($qf as $field_name) {
                    if ($name==$field_name and mysql_escape_string($value)==$value) {
                        $out_query[]=" $name = '$value'";
                    }
                }
            }
        } 
        $query= implode(" and ",$out_query);
        if ($db->query("DELETE FROM ".$collections[$p[0]]." where $query")) {
            $response->sendStatusHeader='HTTP/1.1 202 Accepted';
            $response->responseArray=array('status' => 'deleted', 'destroyed_range' => $query);
            $response->finalSend();
        } else {
            Generic::httpError('HTTP/1.1 404 Not Found','404','');
        } 
    }
    break;
}
?>