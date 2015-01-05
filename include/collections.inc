<?php
/*
for every collection mapped to the Database there has to be a line:
$collections[{collection_name}]={table_name}
There also has to be a line defining accessible fields (also used for URI whitelisting)
$fields[{collection_name}]="{field1},{field2},..."
$pk[{collection_name}]="{primary_key}" (optional for custom collections)
if you want to override the default database wrapper behavior
you can declare explicitely $collections[{collection_name}]='custom'
custom collections will include a {collection_name}.php file and ignore
the standard request handling and response code
*/

$collections = array();
$fields = array();
$pk=array();
$allowed_methods=array();
$dbtype=array();



require "include/collections/amka.php";
require "include/collections/codings.php";
require "include/collections/helpers.php";
require "include/collections/misc.php";
require "include/collections/security.php";

$apiFiles=Generic::getDirectoryList('config/autoapi');

foreach ($apiFiles as $value) {
	$apiConfig=new Conf("config/autoapi/{$value}");
	$collections[$value]=$apiConfig->get('table');
	$fields[$value]=$apiConfig->get('fields');
	$pk[$value]=$apiConfig->get('primaryKey');
	$allowed_methods[$value]=$apiConfig->get('allowedMethods');
	$dbtype[$value]=$apiConfig->get('dbType');
	$auth[$value]=$apiConfig->get('auth');

}

//auth
$collections['auth']='custom';
$allowed_methods['auth']='POST';

//patients test
$collections["patients"]="CS_PATIENTS_TABLE";
$fields["patients"]="OPAT_ID,OPAT_CODE,OPAT_FIRST_NAME,OPAT_LAST_NAME,OPAT_FATHER_NAME,OPAT_AMKA,OPAT_SEX_CD";
$pk['patients']="OPAT_ID";
$allowed_methods['patients']='GET,PUT';
$dbtype['patients']='orcl';

//keystore test
$collections['keystore']='STAVROS_KEYSTORE';
$fields['keystore']='KEY,VALUE';
$pk['keystore']='KEY';
$allowed_methods['keystore']='ALL';
$dbtype['keystore']='orcl';
//$allowed_ips['keystore']=array("127.0.0.","128.0.0.4","129.0.0.1");

//protocol test
$collections['protocol']='PROT.PROTOCOL';
$fields['protocol']='PROT_ID,PROT_DIRECTION,PROT_CODE,PROT_YEAR,PROT_DATE_CREATED,PROT_TITLE';
$pk['protocol']='PROT_ID';
$dbtype['protocol']='orcl';
$allowed_methods['protocol']='GET';

$collections['announce']='custom';
$allowed_methods['announce']='POST,GET';

$collections['test_func']='orcl_func';
$allowed_methods['test_func']='GET';
$oracleFunction['test_func']='CS.WH_TRANSACTION_PCK.FIND_LAST_KIND_IM_UPR';
$ofArgs['test_func']='comp_id,warehouse_id,year,kind_id,upr,mes';

$collections['TestClass']='wr_class';
$allowed_methods['TestClass']='GET';