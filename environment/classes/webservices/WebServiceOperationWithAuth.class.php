<?php

	/**
	 * Description of WebServiceOperationWithAuth
	 *
	 * @author awebtech
	 */
	abstract class WebServiceOperationWithAuth extends WebServiceOperation {
		function  __construct($args) {
			$soap_header = $args[1];

			if (!empty($soap_header['token'])) {
				$credentials = unserialize(base64_decode($soap_header['token']));
				if (!empty($credentials) && is_array($credentials)) {
					foreach ($credentials as $k => $v) {
						$_COOKIE[$k] = $v;
					}
					CompanyWebsite::instance()->init();
				}
			}

			$user = CompanyWebsite::instance()->getLoggedUser();
			if (!$user instanceof User) {
				throw new WebServiceFault('Client', 'Authorization failed');
			}
		}
	}

?>
