<?php

	/**
	 * Description of WebServiceOperationWithAuth
	 *
	 * @author awebtech
	 */
	abstract class WebServiceOperationWithAuth extends WebServiceOperation {
		function  __construct($args) {			
			$args = current($args);
			
			//print_r($args);die();

			if (empty($args['token'])) {
				throw new WebServiceFault('Client', 'Authorization failed');
			}

			$credentials = unserialize(base64_decode($args['token']));
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
