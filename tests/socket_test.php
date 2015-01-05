<?php

/* Get the port for the WWW service. */

echo "<pre>";

$service_port = "42011";

/*if you already know the destination port, you don’t need getservbyname, you can do this: $service_port = port;

Example: $service_port="10000″;*/

/* Get the IP address for the target host. */



/*if you already know the ip of the destination, you don’t need gethostbyname, you can do this: $address = ip address;

Example: $address = 192.168.10.1 */

/* Create a TCP/IP socket. */

$address=gethostbyname("sandbox.e-imo.com");

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

echo "<pre>";

if ($socket === false) {

	echo "socket_create() failed: reason: " . socket_strerror(socket_last_error).  "\n";

} else {

	echo "Socket Creation OK.\n";

}

echo "Attempting to connect to $address on port $service_port";

$result = socket_connect($socket, $address, $service_port);

if ($result === false) {

	echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";

} else {

	echo " Connection OK\n";

}

//$in = "search^10|5|2^A02.9|DistinctBy(ICD10CM_CODE)^be1e9a221855ee18\n";

$in = "detail^54089^1^be1e9a221855ee18\n";

$out = "";

echo "Sending message...";

//socket_write($socket, $in, strlen($in));
//echo " OK.\n";

if( ! socket_send ( $socket , $in , strlen($in) , 0))
{
	$errorcode = socket_last_error();
	$errormsg = socket_strerror($errorcode);

	die("Could not send data: [$errorcode] $errormsg \n");
}

echo "Message send successfully \n";

echo "Reading response:\n\n";

//$out = socket_read($socket, 4,PHP_BINARY_READ) ;
//echo $out. "\n\n";

if(socket_recv ( $socket , $buf , 2046 , MSG_WAITALL ) === FALSE)
{
	$errorcode = socket_last_error();
	$errormsg = socket_strerror($errorcode);

	die("Could not receive data: [$errorcode] $errormsg \n");
}

//print the received message
//echo $buf. "\n\n";
//echo strlen($buf). "\n\n";

if(socket_recv ( $socket , $buf , 204600 , MSG_WAITALL ) === FALSE)
{
	$errorcode = socket_last_error();
	$errormsg = socket_strerror($errorcode);
	//atom test

	die("Could not receive data: [$errorcode] $errormsg \n");
}

//print the received message
//echo $buf. "\n\n";
echo strlen($buf). "\n\n";
//
$xml = simplexml_load_string("<?xml version='1.0'?><Result>".$buf.'</Result>');
//echo htmlentities($buf). "\n\n";
var_dump($xml);
echo "\n\n";
echo $xml->ICD9_LEXICALS_TEXT_IMO->RECORD->ICD9_LEXICALS_TEXT_IMO_CODE;
echo "\n\n";
echo $xml->ICD9_DEFINITIONS_IMO->RECORD->DEFINITION_TEXT;
echo "\n\n";
echo "Closing socket...";

socket_close($socket);

echo "OK.\n\n";

?>
