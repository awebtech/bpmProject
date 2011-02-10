<?php

	//$a = array('http___localhost_bpmProjectid' => 1, 'http___localhost_bpmProjecttoken' => '2daa6e5c3e5eb7ee606075ba6f61a4af8eabfdb5');
	
	//$a = base64_encode(serialize($a));

	require './lib/nusoap.php';

	$client = new soapclient('http://localhost/bpmProject/public/webservices/Auth?wsdl', true);	
	$result = $client->call('Auth.Login', array('login' => 'root', 'password' => 'root'));
	
	$client = new soapclient('http://localhost/bpmProject/public/webservices/Milestone?wsdl', true);
	$token = new soapval('token', 'xsd:string', $result);
	$client->setHeaders(array($token));
	$result = $client->call('Milestone.Create', array('name' => 'Новый проект'));

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

	echo '<h2>Request</h2>';
	echo '<pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
	echo '<h2>Response</h2>';
	echo '<pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
	// Display the debug messages
	echo '<h2>Debug</h2>';
	echo '<pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';

?>
