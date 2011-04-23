<?php	

	//error_log('server: '.print_r($_SERVER, true));
	//error_log('raw post: '.print_r($HTTP_RAW_POST_DATA, true));
	//error_log('post: '.print_r($_POST, true));

	define('CONSOLE_MODE', true);

	$server = new SoapServer(NULL, array('uri' => 'example.com'));

	$valid_services = array('Milestone');

	$service = trim($_SERVER['REQUEST_URI'], '//');
	$service = explode('/', $service);
	$service = end($service);

	if (!in_array($service, $valid_services)) {
		$server->fault('SF-0001', 'Incorrect service name');
	}

	try {
		require realpath(dirname(__FILE__).'/../../').DIRECTORY_SEPARATOR.'index.php';

		$wsdl_uri = './wsdl/'.$service.'.wsdl';
		$server = new SoapServer($wsdl_uri);
		$server->setClass($service);		
		$server->handle();
	} catch (WebServiceFault $e) {
		$server->fault($e->getFaultCode(), $e->getFaultString());
	} catch (Exception $e) {
		$server->fault($e->getCode(), $e->getMessage());
	}

?>