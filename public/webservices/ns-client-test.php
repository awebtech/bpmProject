<?php

	require './lib/nusoap.php';

	$client = new soapclient('http://localhost/bpmProject/public/webservices/ns-server-test.php?wsdl', true);

	$result = $client->call('testRun.run', array('name' => 'Scott'));

	if ($client->fault) {
		echo '<h2>Fault</h2><pre>';
		print_r($result);
		echo '</pre>';
	} else {
		$err = $client->getError();
		if ($err) {
			echo '<h2>Error</h2><pre>' . $err . '</pre>';
		} else {
			echo '<h2>Result</h2><pre>';
			print_r($result);
			echo '</pre>';
		}
	}

?>
