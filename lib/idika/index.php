<?php
ini_set('display_errors',E_ALL); 
require_once('../lib/FirePHPCore/FirePHP.class.php');
$firephp = FirePHP::getInstance(true);

$firephp->info(memory_get_usage().' ..','Memory Usage');

require_once './a2d.php';

$a2d = new A2D("ygeiaa2dl", "c0ms0l", "http://www.idika.org.gr/webservices/AMKA/A2D_WS/service.asmx?WSDL");

$client = $a2d->client();

$firephp->info((string)$client,'Client Function result');

	//echo $client;

$data = array(

	"amka"=>"15127402699"
	);

print_r($a2d->call($data));

?>