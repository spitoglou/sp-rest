<?php

/**
 * oo_index.php
 * @author Stavros Pitoglou
 */

 

// custom class dir
define('CLASS_DIR', 'classes/');
set_include_path(get_include_path().PATH_SEPARATOR.CLASS_DIR);
set_include_path(get_include_path() . PATH_SEPARATOR . 'lib/phpseclib');

//spl_autoload_extensions('.class.php');
//spl_autoload_register();
spl_autoload_register(function ($class) {
    require  str_replace("\\", PATH_SEPARATOR, $class) . '.class.php';
});


/*
include 'classes/Generic.class.php'; //Request Class
include 'classes/Request.class.php'; //Request Class
include 'classes/Response.class.php'; //Response Class
include 'classes/ErrorResponse.class.php'; //ErrorResponse Class
*/

//load composer extensions
//require 'vendor/autoload.php';

require "include/config.inc"; //Configuration File

require "include/collections.inc"; // Collections Definitions
require "lib/class.array2xml2array.php"; //Array2Xml

$request=new Request;

require "include/db_conn.inc"; //Database connection

/*if ($config['cs_auth_enable'] and $request->path[0]!='auth') {
    include "cs_aws_auth.php";
}*/

if ($config['basicAuthEnable'] or $auth[$request->path[0]]=='basic') {
    require "include/basicauth.inc";
}

$domainPath='';
if ($domain=$domain[$request->path[0]]) {
    $domainPath="domains/{$domain}/";
}
switch ($collections[$request->path[0]]) {
    case 'custom': 
    include $domainPath.$request->path[0].'.ccl';
    break;
    case 'atom':
    include $request->path[0].'.ccl';
    include 'include/atom_publish.inc';
    break;
    case 'orcl_func':
    include 'include/orcl_func.inc';
    break;
    case 'wr_class':
    include 'include/class.inc';
    break;
    default: 
    include 'include/wrapper.inc';
    break;
}

?>