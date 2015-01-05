<?php
$start = microtime(true);
ini_set('error_reporting', E_ALL);
error_reporting(E_ALL);
?>

<!doctype html>

<html>

<head>
	<title>SandBox</title>
	<meta name="viewport" content="width=device-width">
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js" type="text/javascript"></script>
	<script src="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js" type="text/javascript"></script>
	<style type="text/css">
		body {
			padding-top: 50px;
			padding-bottom: 20px;
		}
	</style>
</head>

<body>
	<div class="navbar clearfix navbar-fixed-top navbar-inverse">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">SP-Rest Sandbox Page</a>
			</div>
			
		</div>
	</div>
	<div class="container-fluid">
		<section>
			<div class="row clearfix">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading"> 
							<i class="fa fa-play"></i> <?php echo "Test Config Class"; ?><br>
						</div>
						<div class="panel-body">
							<?php
							include_once 'classes/Conf.class.php';
							$test_conf=new Conf('archives/test_conf.xml');
							echo $test_conf->get('user');
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="row clearfix">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading"> 
							<i class="fa fa-play"></i> <?php echo "Test Memcache"; ?><br>
						</div>
						<div class="panel-body">
							<?php
							try {
								$memcache = new Memcache;
							@$memcache->connect('localhost', 11211) or die ("Could not connect to Memcache");

							$version = $memcache->getVersion();
							echo "Memcache Server's version: ".$version."<br/>\n";

							$tmp_object = new stdClass;
							$tmp_object->str_attr = 'test';
							$tmp_object->int_attr = 123;

							if (!$get_result=$memcache->get('key')) {
								$cache_message='Did Not Use Cache';
								$memcache->set('key', $tmp_object, false, 10) or die ("Failed to save data at the server");
								echo "Store data in the cache (data will expire in 10 seconds)<br/>\n";

								$get_result = $memcache->get('key');
								echo "(now)Data from the cache:<br/>\n";
							} else {
								$cache_message='Used Cache';
								echo "(before)Data from the cache:<br/>\n";
							}
							var_dump($get_result);
							} catch (Exception $e) {
								echo 'Having some kind of trouble with Memcache';
								var_dump($e);
							}
							
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="row clearfix">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading"> 
							<i class="fa fa-play"></i> <?php echo "FirePHP (check FirePHP console)"; ?><br>
						</div>
						<div class="panel-body">
							<?php
							require_once('lib/FirePHPCore/FirePHP.class.php');
							$firephp = FirePHP::getInstance(true);

							if (0) $firephp->setEnabled(false);

							$firephp->info(memory_get_usage().' ..','Memory Usage');
							$firephp->info($cache_message);
							$firephp->warn($cache_message);
							$firephp->error($cache_message);
							$firephp->log($get_result,'Cached Data');
							$firephp->info(realpath_cache_size().' bytes','Realpath Cache Size');
							$firephp->info(strval(memory_get_usage()/1000000).' Mb','Memory Usage');

							?>
						</div>
					</div>
				</div>
			</div>
			<div class="row clearfix">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading"> 
							<i class="fa fa-play"></i> <?php echo "Test Regular Expressions"; ?><br>
						</div>
						<div class="panel-body">
							<?php
							$string='ΣταύροςERR|bla|AR';

							if (preg_match('/^Σταύ/', $string)) {
								echo '1:true';
							} else {
								echo '1:false';
							}
							//$firephp->info(memory_get_usage().' ..','Memory Usage');
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="row clearfix">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading"> 
							<i class="fa fa-play"></i> <?php echo "Test SQLite DB"; ?><br>
						</div>
						<div class="panel-body">
							<?php
							print_r(SQLite3::version());
							$handle = new SQLite3('test.s3db');
							$result = $handle->query('SELECT * FROM test');
							$resx = $result->fetchArray(SQLITE3_ASSOC); 
							var_dump($resx); 
							$resx = $result->fetchArray(SQLITE3_ASSOC); 
							var_dump($resx['value']);

							?>
						</div>
					</div>
				</div>
			</div>
			<div class="row clearfix">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading"> 
							<i class="fa fa-play"></i> <?php echo "Test SharePoint lists"; ?><br>
						</div>
						<div class="panel-body">
						<pre>
							<?php
								require_once('lib/SharePointAPI.php');
								$sp = new SharePointAPI('spitoglou@cssa.apps4rent.info', 'potemkin', 'http://sharepoint.computersolutionssa.apps4rent.info/_vti_bin/Lists.asmx?WSDL', true);

								//$sp->read('<list_name>', 10); 
								$lists=$sp->getLists();
								//var_dump($lists);
								//var_dump($sp->read("Περιστατικά (Cases)", 10)); 
								//var_dump($test=$sp->readListMeta("Περιστατικά (Cases)"));
								$test=$sp->readListMeta("Περιστατικά (Cases)");
								echo html_entity_decode($test[16]['name']);
								//echo preg_replace('/[^!-%\x27-;=?-~ ]/e', '"&#".ord("$0").";"', html_entity_decode($test[16]['name']));
							?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<hr>
		<footer>
			<p class="pull-right text-warning"> 
				<?php 
				$time_taken = microtime(true) - $start;
				//$firephp->info($time_taken.' secs','Execution Time');
				echo 'Execution in '.$time_taken.' secs    ';

				?>
				&copy; Computer Solutions 2013
			</p>
		</footer>
	</div>
</body>

</html>

<?php 

//$firephp->info(memory_get_peak_usage().' ..','Memory Peak Usage');


?>