<?php	

	//error_log('server: '.print_r($_SERVER, true));
	//error_log('raw post: '.print_r($HTTP_RAW_POST_DATA, true));
	//error_log('post: '.print_r($_POST, true));

	define('CONSOLE_MODE', true);

	try {
		$ws_web_dir = dirname($_SERVER['PHP_SELF']).'/';

		$service = str_replace($ws_web_dir, '', $_SERVER['REDIRECT_URL']);

		$service = explode('/', $service);
		$service = current($service);

		// to fix error with nusoap wsdl view
		$_SERVER['SCRIPT_NAME'] = $_SERVER['REDIRECT_URL'];
		$_SERVER['PHP_SELF'] = $_SERVER['REDIRECT_URL'];

		require './lib/nusoap.php';

		require realpath(dirname(__FILE__).'/../../') . DIRECTORY_SEPARATOR . 'index.php';

		$server = new soap_server();
		$server->configureWSDL($service, 'tns');
		$service::register($server);

		$HTTP_RAW_POST_DATA = file_get_contents("php://input");
		$server->service($HTTP_RAW_POST_DATA);

		exit;
	} catch (WebServiceFault $e) {
		$server->fault($e->getFaultCode(), $e->getFaultString());
	} catch (Exception $e) {
		$server->fault('Server', $e->getMessage());
	}

	$server->send_response(); // actually send_response() is private, but I do not see any other fast and reliable solution

	die();

?>
