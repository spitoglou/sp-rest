<?php
    switch ($config['dbtype']) {
        case 'mysql':
            //connection to mysql database
            $db = new ezSQL_mysql($config['dbuser'],$config['dbpass'],$config['database'],$config['dbhost']);
            // needed to display greek characters correctly
            $db->query("SET NAMES utf8") ;
            $db->query("SET CHARACTER SET utf8") ;
            break;
        case 'orcl':
            //connection to oracle database
            //putenv('NLS_LANG=AMERICAN_AMERICA.EL8ISO8859P7');
            putenv('NLS_LANG=GREEK_GREECE.AL32UTF8');
            $db = new ezSQL_oracle8_9($config['dbuser'],$config['dbpass'],$config['service']);
            break;
    }


?>