<?php

	require './lib/nusoap.php';

	class testRun {
		function run($action) {
			return "I have run $action<br />\n";
		}

		function fake() {
			echo "fake<br />\n";
		}

	}
	$server = new soap_server();
	$server->configureWSDL('testRun');
	$server->register('testRun.run', array('name' => 'xsd:string'), array('return' => 'xsd:string'));
	$server->register('testRun.fake');

	if (empty($HTTP_RAW_POST_DATA)) {
		$HTTP_RAW_POST_DATA = '';
	}

	$server->service($HTTP_RAW_POST_DATA);

?>
