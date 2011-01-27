<?php

	/**
	 * Description of WebServiceControler
	 *
	 * @author awebtech
	 */
	class WebServiceControler extends Controller {
		private $server = null;
		
		function  __construct() {
			$this->server = new soap_server();
		}

		function error() {
			echo 'error';
		}

		function routeError() {
			echo 'routeError';
		}
	}
?>
