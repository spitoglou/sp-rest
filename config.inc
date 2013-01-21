<?php
  /**
  * sp-rest configuration file
  * 
  * @author Stavros Pitoglou
  */
  
  
  $config=array();
  
  $config['dbtype']='orcl';    //'mysql' or 'orcl'
  $config['dbhost']='localhost'; //mysql
  $config['database']='rest_test'; //mysql
  $config['service']='(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=csdb1)(PORT=1521))(CONNECT_DATA=(SERVICE_NAME=csorcl)))'; //orcl
  $config['dbuser']='CS';  //regardless db
  $config['dbpass']='CS';  //regardless db
?>