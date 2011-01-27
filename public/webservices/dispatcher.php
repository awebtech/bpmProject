<?php

	define('CONSOLE_MODE', true);

	$ws_web_dir = basename($_SERVER['PHP_SELF']);
	$service = str_replace($ws_web_dir, '', $_SERVER['REQUEST_URI']);
	$service = explode('/', $service);

	$_GET['c'] = 'WebServiceController';
	$_GET['a'] = 'routeError';

	if (count($service) == 2) {
		$_GET['c'] = 'Ws'.$service[0];
		$_GET['a'] = $service[1];
	}

	require './lib/nusoap.php';
	require realpath(dirname(__FILE__) . '/../../') . DIRECTORY_SEPARATOR . 'index.php';

	

	print_r($_SERVER);

?>
