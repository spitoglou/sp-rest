<?php
require_once('lib/simpletest/autorun.php');
require_once('lib/simpletest/web_tester.php');

define('CLASS_DIR', 'classes/');
set_include_path(get_include_path().PATH_SEPARATOR.CLASS_DIR);
spl_autoload_extensions('.class.php');
spl_autoload_register();

SimpleTest::ignore('WebTestCase');

$endpoint='http://localhost:81/bbsp-rest/oo_index.php';

class TestOfHelperClasses extends UnitTestCase {

	
	function testTestClassIsConstructedOK() {
		//echo 'test1';
		$test=new TestClass('stavros');
		$this->assertEqual('stavros',$test->var_id);
	}

	function testConf() {
		$test =new Conf('config/main_config.xml');
		$this->assertNotNull($test->get('service'));
	}
}

class TestRestEndpoints extends WebTestCase {

	function testTestNoDatabaseCalls() {
		$calls=array();
		//$endpoint='http://localhost:81/bbsp-rest/oo_index.php';
		global $endpoint;

		$calls[]=array('call'=>'/gmdn','exp_code'=>'200','exp_body'=>'240101_1311_46969','accept' => 'application/json');

		$calls[]=array('call'=>'/cpv','exp_code'=>'200','exp_body'=>'15931000-3','accept' => 'application/json');

		$calls[]=array('call'=>'/cpv?cpv_code=1598','exp_code'=>'200','exp_body'=>'15980000-1','accept' => 'application/json');

		$calls[]=array('call'=>'/cpv?cpv_code=1598a','exp_code'=>'404','exp_body'=>'Empty Resultset','accept' => 'application/json');

		$calls[]=array('call'=>'/cpvasa','exp_code'=>'400','exp_body'=>'not a valid collection','accept' => 'application/json');

		//$calls[]=array('call'=>'/patients?limit=10','exp_code'=>'200','exp_body'=>'opat_first_name','accept' => 'application/json', 'db' => true);

		//$calls[]=array('call'=>'/patients/123/123','exp_code'=>'400','exp_body'=>'Too many','accept' => 'application/json', 'db' => true);

		//$calls[]=array('call'=>'/mpr_details?master_type=med&pat_id=180939&date_from=01/01/2000&date_to=31/12/2013','exp_code'=>'200','exp_body'=>'24\/01\/11','accept' => 'application/json', 'db' => true);

		//$calls[]=array('call'=>'/dash_masters?date_from=01/01/2013&date_to=31/01/2013','exp_code'=>'200','exp_body'=>'"masterid":"3","title"','accept' => 'application/json', 'db' => true);

		$calls[]=array('call'=>'/check_amka/08024700562','exp_code'=>'200','exp_body'=>'YES','accept' => 'application/cs+xml');

		$calls[]=array('call'=>'/data2amka?surname=ΠΙΤΟΓΛΟΥ&firstname=ΣΤΑΥΡΟΣ&afm=107634125','exp_code'=>'200','exp_body'=>'15127402699','accept' => 'application/cs+xml');

		$calls[]=array('call'=>'/amka2data?amka=15127402699','exp_code'=>'200','exp_body'=>'PITOGLOU','accept' => 'application/json');

		//$calls[]=array('call'=>'/diavgeia_test?org=gnl','exp_code'=>'200','exp_body'=>'protocolNumber','accept' => 'application/json');

		//$calls[]=array('call'=>'/test_atom','exp_code'=>'200','exp_body'=>'2013-12-15','accept' => 'application/json');

		$calls[]=array('call'=>'/check_amka/15127402699','exp_code'=>'200','exp_body'=>'"amka_valid":"YES"','accept' => 'application/json');

		$calls[]=array('call'=>'/check_amka/151274026991','exp_code'=>'200','exp_body'=>'AMKA must be 11 characters long','accept' => 'application/json');

		$calls[]=array('call'=>'/check_amka/15127402698','exp_code'=>'200','exp_body'=>'Check Algorithm Failure','accept' => 'application/json');

		$calls[]=array('call'=>'/TestClass/6542123/testFunc2/1,2','exp_code'=>'200','exp_body'=>'arg1=1 , arg2=2','accept' => 'application/json');

		$calls[]=array('call'=>'/edapy/get/1408291613487','exp_code'=>'200','exp_body'=>'8706758',accept => 'application/json');

		$calls[]=array('call'=>'/imoproblem/searchicd/A001','exp_code'=>'200','exp_body'=>'Cholera',accept => 'application/json');

		$calls[]=array('call'=>'/imoproblem//detail/1811072','exp_code'=>'200','exp_body'=>'Cholera',accept => 'application/json');

		$calls[]=array('call'=>'/orcl_func_test','exp_code'=>'200','exp_body'=>'"Execute Statement":"Succesful"',accept => 'application/json');

		
		
		$this->addHeader("Accept : application/json");

		foreach ($calls as $key => $value) {
			echo $value['call'].'<br>';
			$this->assertTrue($this->get("{$endpoint}{$value['call']}"));
			$this->assertText($value['exp_body']);
			$this->assertHeader('X-Powered-By','Computer Solutions Web Services');
			$this->assertResponse((int)$value['exp_code']);

		}


	}
}
?>