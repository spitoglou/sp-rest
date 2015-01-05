<?php

// custom class dir
define('CLASS_DIR', 'classes/');
set_include_path(get_include_path().PATH_SEPARATOR.CLASS_DIR);
spl_autoload_extensions('.class.php');
spl_autoload_register();

include("include/collections.inc");

ini_set("soap.wsdl_cache_enabled", "0"); // disabling WSDL cache
$server = new SoapServer("http://localhost:81/bbsp-rest/wsdl.php");
foreach ($collections as $key => $value) {
	$string = 'function ' . $key . '($args=\'test=1\') { 
		$ch = curl_init(\''."http://localhost:81/bbsp-rest/oo_index.php/{$key}?".'{$args}\');
		//echo $args;

		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_HEADER=>1,
			CURLOPT_USERAGENT => \'CSSA Call Testing\',
			CURLOPT_HTTPHEADER => array(\'Accept: application/json\')
			));

$resp=curl_exec($ch);

if(!curl_errno($ch))
{
	$info = curl_getinfo($ch);
	$header_size = $info[\'header_size\'];
	$header = substr($resp, 0, $header_size);
	$body = substr($resp, $header_size);
	$headers=explode("\r\n", $header);
	return var_export(json_decode($body),true);
	//return $body;
	

} else {
	return \'CURL ERROR!!!\';
} }';
eval($string);
$server->addFunction($key);
}

$server->handle();