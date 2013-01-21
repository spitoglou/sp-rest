<?php
  //for every collection mapped to the Database there has to be a line:
    //$collections[{collection_name}]={table_name}
    // There also has to be a line defining accessible fields (also used for URI whitelisting)
    //$fields[{collection_name}]="{field1},{field2},..."
    //$pk[{collection_name}]="{primary_key}"
    $collections = array();
    $fields = array();  
    $collections["test"]="CS_PATIENTS_TABLE";
    $fields["test"]="OPAT_ID,OPAT_FIRST_NAME,OPAT_LAST_NAME";
    $pk['test']="OPAT_ID";
?>