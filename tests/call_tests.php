<?php
error_reporting(0);
?>

<!doctype html>

<html>

<head>
	<title>Sp-Rest Call Tests</title>
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
		.btn-label {position: relative;left: -12px;display: inline-block;padding: 6px 12px;background: rgba(0,0,0,0.15);border-radius: 3px 0 0 3px;}
		.btn-labeled {padding-top: 0;padding-bottom: 0;}
		.btn { margin-bottom:10px; }
	</style>
</head>

<body>
	<div class="navbar clearfix navbar-fixed-top navbar-inverse">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Sp-Rest Call Tests</a>
			</div>
			
		</div>
	</div>
	<?php

	$calls=array();
//endpoints
	$endpoint='http://localhost:81/bbsp-rest/oo_index.php';

	//$endpoint='http://www.cssa-web-services.tk/ws/v1/oo_index.php';
	//$endpoint='http://192.168.1.116:8080/bbsp-rest/oo_index.php';



//calls
	$calls[]=array('call'=>'/gmdn','exp_code'=>'200','exp_body'=>'240101_1311_46969','accept' => 'application/json');

	$calls[]=array('call'=>'/cpv','exp_code'=>'200','exp_body'=>'15931000-3','accept' => 'application/json');

	$calls[]=array('call'=>'/cpv?cpv_code=1598','exp_code'=>'200','exp_body'=>'15980000-1','accept' => 'application/json');

	$calls[]=array('call'=>'/cpv?cpv_code=1598a','exp_code'=>'404','exp_body'=>'Empty Resultset','accept' => 'application/json');

	$calls[]=array('call'=>'/cpvasa','exp_code'=>'400','exp_body'=>'not a valid collection','accept' => 'application/json');

	$calls[]=array('call'=>'/check_amka/08024700562','exp_code'=>'200','exp_body'=>'<amka_valid>YES</amka_valid>','accept' => 'application/cs+xml');

	$calls[]=array('call'=>'/data2amka?surname=ΠΙΤΟΓΛΟΥ&firstname=ΣΤΑΥΡΟΣ&afm=107634125','exp_code'=>'200','exp_body'=>'15127402699','accept' => 'application/cs+xml');

	$calls[]=array('call'=>'/amka2data?amka=15127402699','exp_code'=>'200','exp_body'=>'PITOGLOU','accept' => 'application/json');

	$calls[]=array('call'=>'/test_atom','exp_code'=>'200','exp_body'=>'<updated>','accept' => 'application/json');

	$calls[]=array('call'=>'/check_amka/15127402699','exp_code'=>'200','exp_body'=>'"amka_valid":"YES"','accept' => 'application/json');

	$calls[]=array('call'=>'/check_amka/151274026991','exp_code'=>'200','exp_body'=>'AMKA must be 11 characters long','accept' => 'application/json');

	$calls[]=array('call'=>'/check_amka/15127402698','exp_code'=>'200','exp_body'=>'Check Algorithm Failure','accept' => 'application/json');

	$calls[]=array('call'=>'/TestClass/6542123/testFunc2/1,2','exp_code'=>'200','exp_body'=>'arg1=1 , arg2=2','accept' => 'application/json');

	$calls[]=array('call'=>'/ekapty?ekapty=430499','exp_code'=>'200','exp_body'=>'430499','accept' => 'application/json');

	$counter=0;
	$failed=0;
	//header( 'Content-type: text/html; charset=utf-8' );
	?>
	<div class="container-fluid">
		<section>
			<div class="row clearfix">
				<?php

				foreach ($calls as $key => $value) {
					if ($_GET['no_db'] and $value['db']) continue;
					?>
					
					<div class="col-md-6">
						<div class="panel panel-default">
							<div class="panel-heading"> 
								<i class="fa fa-play"></i> <?php echo 'Test no '. ++$counter; ?>
							</div>
							<div class="panel-body">
								<?php

								$failFlag=false;

								echo 'Testing '.$endpoint.$value['call'].'<br>';
								$ch = curl_init($endpoint.$value['call']);

								curl_setopt_array($ch, array(
									CURLOPT_RETURNTRANSFER => 1,
									CURLOPT_HEADER=>1,
									CURLOPT_USERAGENT => 'CSSA Call Testing',
									CURLOPT_HTTPHEADER => array('Accept:'.$value['accept'])
									));

								$resp=curl_exec($ch);

								if(!curl_errno($ch))
								{
									$info = curl_getinfo($ch);
									$header_size = $info['header_size'];
									$header = substr($resp, 0, $header_size);
									$body = substr($resp, $header_size);
									$headers=explode("\r\n", $header);
									echo 'Status Code: '.$info['http_code'];
									if ($info['http_code']==$value['exp_code']) {
										echo ' <span class="text-success">As Expected</span>';
									} else {
										$failFlag=true;
										echo ' <span class="text-warning">NOT AS EXPECTED (Fail)</span>';
									}
									echo '<br>';

									if (array_search('X-Powered-By: Computer Solutions Web Services', $headers)) {
										echo ' <span class="text-success">Found CS Web Services Header</span>';
									} else {
										$failFlag=true;
										echo ' <span class="text-warning">NOT FOUND CS Web Services Header (Fail)</span>';
									}
									echo '<br>';
		//echo rawurldecode($body).'<br>';
									echo 'Expected String in Response Body: '.$value['exp_body'];
									if (mb_stripos($body, $value['exp_body'])) {
										echo ' <span class="text-success">Found As Expected</span>';
									} else {
										$failFlag=true;
										echo ' <span class="text-warning">NOT FOUND AS EXPECTED (Fail)</span>';
									}
									echo '<br>';
									echo 'Took ' . $info['total_time'] . ' seconds';

								} else {
									$failFlag=true;
									echo '<p class="text-warning">CURL ERROR!!!</p';
								}
								if ($failFlag) {
									$failed++;
									echo '<br><button type="button" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="glyphicon glyphicon-remove"></i></span>Failed</button>';
								} else {
									echo '<br><button type="button" class="btn btn-labeled btn-success"><span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span>Success</button>';
								}
								?>
							</div>
						</div>
					</div>
					
					
					<?php 
				//echo '<hr>';
					flush();
					ob_flush();
					set_time_limit(40);
				}

			//echo '<br><br>Totally run '.$counter.' Tests'.'<br>Passed '.($counter-$failed).' Failed '.$failed;
				ob_end_flush();
				?>
			</div>
			<br>
			<div class="row clearfix">
				<div class="col-md-6">
					<strong>Passed</strong><span class="pull-right"><?php echo $counter-$failed.' test(s) = '.strval(($counter-$failed)/$counter*100); ?>%</span>
					<div class="progress progress-success active">
						<?php echo '<div class="progress-bar progress-bar-success" style="width: '.strval(($counter-$failed)/$counter*100).'%;"></div>'; ?>
					</div>
					<strong>Failed</strong><span class="pull-right"><?php echo $failed.' test(s) = '.strval($failed/$counter*100)?>%</span>
					<div class="progress progress-warning active">
						<?php echo '<div class="progress-bar progress-bar-danger" style="width: '.strval($failed/$counter*100).'%;"></div>'; ?>
					</div>
				</div>
			</div>
		</section>
		<hr>
		<footer>
			<p class="pull-right text-warning">&copy; Computer Solutions 2013</p>
		</footer>
	</div>
</body>
</html>