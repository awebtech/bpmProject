<?php

	//$a = array('http___localhost_bpmProjectid' => 1, 'http___localhost_bpmProjecttoken' => '2daa6e5c3e5eb7ee606075ba6f61a4af8eabfdb5');
	
	//$a = base64_encode(serialize($a));

	require './lib/nusoap.php';

	$client = new soapclient('http://localhost/bpmProject/public/webservices/Auth?wsdl', true);	
	$result = $client->call('Auth.Login', array('login' => 'root', 'password' => 'root'));
	
	$client = new soapclient('http://localhost/bpmProject/public/webservices/Milestone?wsdl', true);
	$token = new soapval('token', 'xsd:string', $result);
	$client->setHeaders(array($token));

	$milestone['milestone'] = array(
		'name' => 'Совсем новый проект',
		'tags' => 'тэг1',
		'description' => 'Описалово',
		'assigned_to' => '1:1',
		'send_notification' => 'checked',
		'is_urgent' => 'checked',
		'due_date_value' => '30/01/2011',
	);

	$milestone['ws_ids'] = 1;
	$milestone['taskFormAssignedToCombo'] = 'Me';

	$milestone['object_custom_properties'] = array(
			1 => '30/01/2011',
	);

	$result = $client->call('Milestone.Create', array('milestone' => $milestone));

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
	echo '<pre>' . htmlspecialchars($client->request) . '</pre>';
	echo '<h2>Response</h2>';
	echo '<pre>' . htmlspecialchars($client->response) . '</pre>';
	// Display the debug messages
	echo '<h2>Debug</h2>';
	echo '<pre>' . $client->debug_str . '</pre>';

?>
