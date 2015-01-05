<?php

$response=new Response;

$result=array();

$p=$request->path;
if (count($p)>4 or count($p)<2) {
	Generic::httpError('HTTP/1.1 400 Bad Request','400','Too many (>4) or too few (<2) path parameters');
}

$object = new $p[0]($p[1]);
//echo '<pre>';
//var_dump($object);

$classVars=get_object_vars($object);
//var_dump($classVars);
//echo $p[2];
if (array_key_exists($p[2], $classVars)) { 
	$result[]=array($p[2]=>$object->$p[2]);
}

$classMethods = get_class_methods($object);
//var_dump($classMethods);
if (in_array($p[2], $classMethods)) { 
	//$result[]=array($p[2]=>$object->$p[2]($p[3]));
	$result[]=array($p[2]=>call_user_func_array(array($object, $p[2]), explode(',',$p[3])));
}

$response->responseArray=$result;
$response->finalSend();