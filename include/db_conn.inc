<?php

include("lib/ezsql/shared/ez_sql_core.php");

switch ($dbtype[$request->path[0]]) {
	case 'mysql':
    //connection to mysql database
	include("lib/ezsql/mysql/ez_sql_mysql.php");
	$db = new ezSQL_mysql($config['my_dbuser'],$config['my_dbpass'],$config['database'],$config['dbhost']);
    // needed to display greek characters correctly
	$db->query("SET NAMES utf8") ;
	$db->query("SET CHARACTER SET utf8") ;
	break;
	case 'orcl':
    //connection to oracle database
    //putenv('NLS_LANG=AMERICAN_AMERICA.EL8ISO8859P7');
	include("lib/ezsql/oracle8_9/ez_sql_oracle8_9.php"); 
	putenv('NLS_LANG=GREEK_GREECE.AL32UTF8');
	$db = new ezSQL_oracle8_9($config['or_dbuser'],$config['or_dbpass'],$config['service']);
	break;
}

?>