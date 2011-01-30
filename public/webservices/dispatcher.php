<?php	

	//error_log('server: '.print_r($_SERVER, true));
	//error_log('raw post: '.print_r($HTTP_RAW_POST_DATA, true));
	//error_log('post: '.print_r($_POST, true));

	define('CONSOLE_MODE', true);

	$ws_web_dir = dirname($_SERVER['PHP_SELF']).'/';

	$service = str_replace($ws_web_dir, '', $_SERVER['REDIRECT_URL']);

	//echo "$service<br>";

	$service = explode('/', $service);	

	$service_name = '';
	if (empty($service)) {
		throw new Exception('Web-service is not set');
	} else {
		$service_name = current($service);
	}

	// to fix error with nusoap wsdl view
	$_SERVER['SCRIPT_NAME'] = $_SERVER['REDIRECT_URL'];
	$_SERVER['PHP_SELF'] = $_SERVER['REDIRECT_URL'];
	
	require './lib/nusoap.php';

	require realpath(dirname(__FILE__) . '/../../') . DIRECTORY_SEPARATOR . 'index.php';

	$_POST['milestone'] = array(
		'name' => 'Совсем новый проект',
		'tags' => 'тэг1',
		'description' => 'Описалово',
          'assigned_to' => '1:1',
          'is_urgent' => 'checked',
          'due_date_value' => '30/01/2011',
	);

	$_POST['ws_ids'] = 1;

	$_POST['object_custom_properties'] = array(
            1 => '30/01/2011',
	);

	$milestone_data = array_var($_POST, 'milestone');
	$user = Users::getByUsername('root', owner_company());
	CompanyWebsite::instance()->logUserIn($user, false);

	$milestone = new ProjectMilestone();

	$milestone_data['due_date'] = getDateValue(array_var($milestone_data, 'due_date_value'),DateTimeValueLib::now()->beginningOfDay());
	$assigned_to = explode(':', array_var($milestone_data, 'assigned_to', ''));
	$milestone->setIsPrivate(false); //Mandatory to set
	$milestone->setFromAttributes($milestone_data);
	$urgent = array_var($milestone_data, 'is_urgent') == 'checked';
	$milestone->setIsUrgent($urgent);

	$project = Projects::findById(array_var($_POST, 'ws_ids', 0));

	$milestone->setAssignedToCompanyId(array_var($assigned_to, 0, 0));
	$milestone->setAssignedToUserId(array_var($assigned_to, 1, 0));

	try {
		DB::beginWork();

		$milestone->save();
		$milestone->setTagsFromCSV(array_var($milestone_data, 'tags'));
		$object_controller = new ObjectController();
		$object_controller->add_to_workspaces($milestone);
		//$object_controller->link_to_new_object($milestone);
		//$object_controller->add_subscribers($milestone);
		$object_controller->add_custom_properties($milestone);
		//$object_controller->add_reminders($milestone);

		ApplicationLogs::createLog($milestone, $milestone->getWorkspaces(), ApplicationLogs::ACTION_ADD);

		DB::commit();
		echo "SUCCESS";
	} catch(Exception $e) {
		DB::rollback();
		echo "EPIC FAIL";
	} // try
	die();

	$service = new WebService($service_name);
	$service->registerOperations();
	$service->processRequest();

?>
