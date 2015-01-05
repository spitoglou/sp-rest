<?php

$orclConfig=new Conf('config/func_config.xml');

$con_str =$orclConfig->get('for_con_str');
$dbuser  =$orclConfig->get('for_dbuser');
$dbpass  =$orclConfig->get('for_dbpass'); 

$response = new Response;

$result=array();
putenv('NLS_LANG=GREEK_GREECE.AL32UTF8');
if (!$cusDb=oci_connect($dbuser , $dbpass,$con_str)) {
	Generic::httpError('HTTP/1.1 500 Internal Server Error','500','Could Not Connect to Database');
}

$p=$request->path;

$function=$oracleFunction[$p[0]];
$args    =array();
$args    =explode(',', $ofArgs[$p[0]]);
$finArgs =array();

foreach ($args as $key => $value) {
	$finArgs[$key]=':'.$value;
}
$argsStr =implode(',', $finArgs);

$sql     = "DECLARE RetVal BOOLEAN;BEGIN RetVal := {$function} ({$argsStr}); END;";
$stmt    = oci_parse($cusDb,$sql);
$bound   = array();

foreach ($finArgs as $key => $value) {
	oci_bind_by_name($stmt,$value,$bound[$key],32);
}

$queryArgs=explode(',', $_GET['args']);

foreach ($queryArgs as $key => $value) {
	$bound[$key]=$value;
}

if (!oci_execute($stmt)) {
	Generic::httpError('HTTP/1.1 500 Internal Server Error','500','Could Not Execute Statement');
}

$res2=array();
foreach ($bound as $key => $value) {
	$res2[$args[$key]]=$value;
}
$result[]=$res2;
$response->responseArray=$result;
$response->finalSend();

?>