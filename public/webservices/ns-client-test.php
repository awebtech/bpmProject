<?php

	//$a = array('http___localhost_bpmProjectid' => 1, 'http___localhost_bpmProjecttoken' => '2daa6e5c3e5eb7ee606075ba6f61a4af8eabfdb5');
	
	//$a = base64_encode(serialize($a));

	//error_reporting(E_ALL);
	//ini_set('display_errors', 'On');

	//print_r($_SERVER);

	$ws_url = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/';

	require './lib/nusoap.php';
	require '../../environment/classes/webservices/WebServiceComplexType.class.php';

	$client = new nusoap_client($ws_url.'Auth?wsdl', true);	
	
	$result = $client->call('Auth.Login', array('login' => 'root', 'password' => 'root'));

	switch ($_GET['action']) {
		case 'Task.Create':
			$client = new nusoap_client($ws_url.'Task?wsdl', true);

			$task['task'] = array(
				'title' => 'Новая поставка 21',
				'tags' => 'тэг1',
				'milestone_id' => 7,
				'priority' => 300,
				'object_subtype' => 2,
				'text' => 'Описалово',
				'assigned_to' => '1:1',
				'send_notification' => 'checked',
			);

			$task['task_start_date'] = '2011-02-18';
			$task['task_due_date'] = '2011-02-28';
			$task['ws_ids'] = 1;
			$task['taskFormAssignedToCombo'] = 'Me';

			$task['token'] = $result;

			/*$task['object_custom_properties'] = array(
					2 => 'checked',
			);

			$task['object_custom_properties'] = WebServiceComplexType::ToKeyValue($task['object_custom_properties']);*/

			$result = $client->call($_GET['action'], array('task' => $task));
			break;
		case 'Task.Update':
			$client = new nusoap_client($ws_url.'Task?wsdl', true);

			$task['id'] = 2;

			$task['task'] = array(
				'title' => 'Новая поставка 21',
				'tags' => 'тэг1',
				'milestone_id' => 7,
				'priority' => 300,
				'object_subtype' => 2,
				'text' => 'Описалово',
				'assigned_to' => '1:1',
				'send_notification' => 'checked',
			);

			$task['task_start_date'] = '2011-02-18';
			$task['task_due_date'] = '2011-02-28';
			$task['ws_ids'] = 1;
			$task['taskFormAssignedToCombo'] = 'Me';

			$task['genid'] = '';//og_1298304592_886994';

			$task['token'] = $result;

			/*$task['object_custom_properties'] = array(
					2 => 'checked',
			);

			$task['object_custom_properties'] = WebServiceComplexType::ToKeyValue($task['object_custom_properties']);*/

			$result = $client->call($_GET['action'], array('task' => $task));
			break;
		case 'Milestone.Create':
			$client = new nusoap_client($ws_url.'Milestone?wsdl', true);

			$milestone = array();
			$milestone['id'] = 0;
			$milestone['milestone'] = array(
				'name' => '',
				'tags' => '',
				'description' => '',
				'assigned_to' => '',
				'send_notification' => '',
				'is_urgent' => '',
				'due_date_value' => '',
				'is_template' => '',
			);
			$milestone['ws_ids'] = 0;
			$milestone['taskFormAssignedToCombo'] = '';
			$milestone['object_custom_properties'] = array(
					'start_date_value' => '',
					'critical_date_value' => '',
			);
			$milestone['updatedon'] = '';

			// Изначально milestone.object_custom_properties.1
			// Переводим в milestone.object_custom_properties.Дата начала, при помощи внутренних функций FO (CustomProperties::getCustomPropertyByName($object_type, $custom_property_name))
			// Переводим в milestone.object_custom_properties.start_date_value, при помощи таблицы соответствий:
			// Prefix							|	mapping1		|	mapping2			|	hash1	|	hash2
			// milestone.object_custom_properties		Дата начала		start_date_value		dskadgfa		dskfjsdjk

			// milestone.object_custom_properties 
			// Дата начала
			// start_date_value
			//
			// milestone.object_custom_properties.start_date_value = Дата начала

			$milestone['milestone'] = array(
				'name' => 'miiiiiiiilestooone',
				'tags' => 'тэг1',
				'description' => 'Описалово',
				'assigned_to' => '1:1',
				'send_notification' => 'checked',
				'is_urgent' => 'checked',
				'due_date_value' => '2011-01-13',
			);

			$milestone['ws_ids'] = 1;
			$milestone['taskFormAssignedToCombo'] = 'Me';

			$milestone['object_custom_properties'] = array(
					1 => '2011-01-01',					
			);

			$milestone['object_custom_properties'] = WebServiceComplexType::ToKeyValue($milestone['object_custom_properties']);

			$milestone['token'] = $result;

			$result = $client->call($_GET['action'], array('milestone' => $milestone));
		break;
		case 'Milestone.Update':
			$client = new nusoap_client($ws_url.'Milestone?wsdl', true);

			$milestone['id'] = 1;

			$milestone['milestone'] = array(
				'name1' => 'Измененный майлстоун',
				'tags' => 'тэг23',
				'description' => 'Описалово123',
				'assigned_to' => '1:1',
				'send_notification' => 'checked',
				'is_urgent' => 'checked',
				'due_date_value' => '2011-01-13',
			);

			$milestone['ws_ids'] = 1;
			$milestone['taskFormAssignedToCombo'] = 'Me';

			$milestone['object_custom_properties'] = array(
					1 => '2013-02-02',
					3 => 10,
			);

			$milestone['object_custom_properties'] = WebServiceComplexType::ToKeyValue($milestone['object_custom_properties']);

			$milestone['updatedon'] = 1297696651;

			$milestone['token'] = $result;

			$result = $client->call($_GET['action'], array('milestone' => $milestone));
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
