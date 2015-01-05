<?php

//error_reporting(E_ERROR);
ini_set('error_reporting', E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);
ini_set('error_log', 'logs/php_error_messages.log');

include 'classes/Generic.class.php';

if ($_GET['test']) {
	require "tests/{$_GET['test']}.php";
} else {
	echo '<pre>';
	print_r(Generic::getDirectoryList('tests'));
}


?>