<?php

	//$a = array('http___localhost_bpmProjectid' => 1, 'http___localhost_bpmProjecttoken' => '2daa6e5c3e5eb7ee606075ba6f61a4af8eabfdb5');
	
	//$a = base64_encode(serialize($a));

	require './lib/nusoap.php';

	$client = new soapclient('http://localhost/bpmProject/public/webservices/Auth?wsdl', true);	
	$result = $client->call('Auth.Login', array('login' => 'root', 'password' => 'root'));

	switch ($_GET['action']) {
		case 'task':
			$client = new soapclient('http://localhost/bpmProject/public/webservices/Task?wsdl', true);
			$token = new soapval('token', 'xsd:string', $result);
			$client->setHeaders(array($token));

			$task['task'] = array(
				'title' => 'Новая поставка 21',
				'tags' => 'тэг1',
				'milestone_id' => 22,
				'priority' => 200,
				'object_subtype' => 1,
				'text' => 'Описалово',
				'assigned_to' => '1:1',
				'send_notification' => 'checked',
			);

			$task['task_start_date'] = '2011/02/18';
			$task['task_due_date'] = '2011/02/28';
			$task['ws_ids'] = 1;
			$task['taskFormAssignedToCombo'] = 'Me';

			$task['object_custom_properties'] = array(
					2 => 'checked',
			);

			$result = $client->call('Task.Create', array('task' => $task));
			break;
		case 'milestone':
			$client = new soapclient('http://localhost/bpmProject/public/webservices/Milestone?wsdl', true);
			$token = new soapval('token', 'xsd:string', $result);
			$client->setHeaders(array($token));

			$milestone['milestone'] = array(
				'name' => 'miiiiiiiilestooone',
				'tags' => 'тэг1',
				'description' => 'Описалово',
				'assigned_to' => '1:1',
				'send_notification' => 'checked',
				'is_urgent' => 'checked',
				'due_date_value' => '2011/01/13',
			);

			$milestone['ws_ids'] = 1;
			$milestone['taskFormAssignedToCombo'] = 'Me';

			$milestone['object_custom_properties'] = array(
					1 => '2011/01/01',
			);

			$result = $client->call('Milestone.Create', array('milestone' => $milestone));
		break;
	}

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
