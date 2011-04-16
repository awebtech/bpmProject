<?php

	/**
	 * Description of WebServiceOperationWithAuth
	 *
	 * @author awebtech
	 */
	abstract class WebServiceOperationWithAuth extends WebServiceOperation {
		function  __construct($args) {			
			//$args = current($args);

			//error_log(print_r($args, true));

			$token = '';
			foreach ($args as $arg) {
				if (is_string($arg) && strlen($arg) == 180) {
					$token = $arg;
					break;
				}
			}

			//print_r($args);die();

			if (empty($token)) {
				throw new WebServiceFault('Client', 'Authorization failed');
			}

			$credentials = unserialize(base64_decode($token));
			if (!empty($credentials) && is_array($credentials)) {
				foreach ($credentials as $k => $v) {
					$_COOKIE[$k] = $v;
				}
				CompanyWebsite::instance()->init();
			}

			$user = CompanyWebsite::instance()->getLoggedUser();
			if (!$user instanceof User) {
				throw new WebServiceFault('Client', 'Authorization failed');
			}
		}
	}

?>
