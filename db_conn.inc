<?php
  //connection to mysql database
    $db = new ezSQL_mysql($config['dbuser'],$config['dbpass'],$config['database'],$config['dbhost']);
    // needed to display greek characters correctly
    $db->query("SET NAMES utf8") ;
    $db->query("SET CHARACTER SET utf8") ;
?>