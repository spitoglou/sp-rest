<?php
  //for every collection mapped to the Database there has to be a line:
    //$collections[{collection_name}]={table_name}
    // There also has to be a line defining accessible fields (also used for URI whitelisting)
    //$fields[{collection_name}]="{field1},{field2},..."
    $collections = array();
    $fields = array();  
    $collections["test"]="rest1";
    $fields["test"]="descr,lname";
?>