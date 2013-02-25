<?php
  /**
  * sp-rest configuration file
  * 
  * @author Stavros Pitoglou
  */
  
  
  $config=array();

  // Database Configuration
  
  $config['dbtype']='orcl';    //'mysql' or 'orcl'
  $config['dbhost']='localhost'; //mysql
  $config['database']='rest_test'; //mysql
  $config['service']='(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.1.70)(PORT=1521))(CONNECT_DATA=(SERVICE_NAME=orcl_dev)))'; //orcl
  $config['dbuser']='PROT';  //regardless db
  $config['dbpass']='PROT';  //regardless db

  
?>