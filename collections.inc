<?php
  //for every collection mapped to the Database there has to be a line:
    //$collections[{collection_name}]={table_name}
    // There also has to be a line defining accessible fields (also used for URI whitelisting)
    //$fields[{collection_name}]="{field1},{field2},..."
    //$pk[{collection_name}]="{primary_key}"
    $collections = array();
    $fields = array();  
    /*$collections["test"]="CS_PATIENTS_TABLE";
    $fields["test"]="OPAT_ID,OPAT_FIRST_NAME,OPAT_LAST_NAME";
    $pk['test']="OPAT_ID";*/
    $collections["test"]="rest1";
    $fields["test"]="id,descr,lname";
    $pk['test']="id";
    $collections['protocol']='PROTOCOL';
    // $fields['protocol']='PROT_ID,PROT_DIRECTION,PROT_CODE,PROT_YEAR,PROT_IN_DATE,PROT_TITLE,';
    $fields['protocol']='PROT_ID,PROT_DIRECTION,PROT_CODE,PROT_YEAR,PROT_DATE_CREATED,PROT_TITLE';
    $pk['protocol']='PROT_ID';
?>