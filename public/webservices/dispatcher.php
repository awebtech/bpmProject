<?php

	print_r($_SERVER);

	define('CONSOLE_MODE', true);

	$ws_web_dir = dirname($_SERVER['PHP_SELF']).'/';

	$service = str_replace($ws_web_dir, '', $_SERVER['REDIRECT_URL']);

	echo "$service<br>";

	$service = explode('/', $service);	

	// If both parameters passed (controller and action)
	if (count($service) == 2) {
		$_GET['c'] = 'Ws'.$service[0];
		$_GET['a'] = $service[1];
	} else if (count($service) == 1) {
		$_GET['c'] = 'Ws'.$service[0];
		$_GET['a'] = '';
	} else {
		$_GET['c'] = 'WebService';
		$_GET['a'] = 'routeError';
	}

	print_r($_GET);

	require './lib/nusoap.php';

	require realpath(dirname(__FILE__) . '/../../') . DIRECTORY_SEPARATOR . 'index.php';

	Env::executeAction(request_controller(), request_action());	

?>
