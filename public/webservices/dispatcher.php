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
		throw new Exception('Web-service is not defined');
	} else {
		$service_name = current($service);
	}

	// to fix error with nusoap wsdl view
	$_SERVER['SCRIPT_NAME'] = $_SERVER['REDIRECT_URL'];
	$_SERVER['PHP_SELF'] = $_SERVER['REDIRECT_URL'];
	
	require './lib/nusoap.php';	

	require realpath(dirname(__FILE__).'/../../') . DIRECTORY_SEPARATOR . 'index.php';

	try {
		$service = new WebService($service_name);
		$service->registerOperations();
		$service->processRequest();
	} catch (WebServiceFault $e) {
		$service->fault($e->getFaultCode(), $e->getFaultString());
	} catch (Exception $e) {
		$service->fault('Server', $e->getMessage());
	}

	die();

	
	die();

?>
