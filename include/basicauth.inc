<?php

/**
 * Basic Authentication
 * @author Stavros Pitoglou <[s.pitoglou@csl.gr]>
 */

/**
 * [authFailure Handle Basic Auth Failure to Authenticate]
 * @param  [string] $text Text to display
 * @return Exits after execution
 */
function authFailure($text) {
	global $config;
	header("WWW-Authenticate: Basic realm={$config['basicAuthRealm']}");
    header('HTTP/1.0 401 Unauthorized');
    echo $text;
    exit;
}

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    authFailure("You did not provide credentials");
} else {
	require 'include/creds/creds.php';
	$user = $_SERVER['PHP_AUTH_USER'];
	$pass = hash('sha256','ws@cs-'.$_SERVER['PHP_AUTH_PW']);
	if ($creds[$user]!==$pass) {
		authFailure('Wrong Credentials!!!');
	}
}

