<?php
/**
* sp-rest configuration file
* 
* @author Stavros Pitoglou
* 
*/

mb_internal_encoding('UTF-8');
$config = array();


//include_once 'classes/Config.class.php';
$main_config=new Conf('config/main_config.xml');

$config['dbhost']   =$main_config->get('dbhost'); //mysql
$config['database'] =$main_config->get('database'); //mysql
$config['my_dbuser']   =$main_config->get('my_dbuser'); //mysql
$config['my_dbpass']   =$main_config->get('my_dbpass');  //mysql

$config['service']  =$main_config->get('service'); //orcl
$config['or_dbuser']   =$main_config->get('or_dbuser'); //orcl
$config['or_dbpass']   =$main_config->get('or_dbpass');  //orcl

// {DEPRECATED}
/**
 * Authentication Configuration
 * @since 2.0
 */

//$config['cs_auth_enable'] = (bool)$main_config->get('cs_auth_enable'); //switch on/off the CS (AWS Style) Authentication
//$config['auth_time_interval'] =$main_config->get('auth_time_interval'); //(minutes) timespan for valid requests (to prevent "replay attacks")
//$config['cs_auth_debug']=(bool)$main_config->get('cs_auth_debug'); //switch on/off logging (debug file) 
// {/DEPRECATED}

$config['basicAuthEnable'] = (bool)$main_config->get('basicAuthEnable'); //switch on/off Basic Authentication
$config['basicAuthRealm'] = $main_config->get('basicAuthRealm'); 
/*--HTTP Digest
If enabled there will be no calculation of the digest response.
A fixed test string will be used instead.
--CS (AWS Style)
If enabled there will be no compare of the hash keys.
A perfect match will be assumed...*/
$config['test_auth_responde_mode'] = (bool)$main_config->get('test_auth_responde_mode');

$config['log']=(bool)$main_config->get('log');
$config['error_reporting']=(bool)$main_config->get('error_reporting');

$config['timeZone']   =$main_config->get('timeZone'); 

date_default_timezone_set($config['timeZone']);

if ($config['error_reporting']) {
    ini_set('error_reporting', E_ALL & ~E_NOTICE);
    ini_set('display_errors', 0);
    ini_set('error_log', 'logs/php_error_messages.log');
} else {
    error_reporting(0);
}

if ($_GET['config_dump']) {
    var_dump($config);
    die();
}
?>