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

		function execute($action) {
			$action = strtolower($action);
			error_log(get_class($this).'.'.$action);
			if (!empty($action)) {
				$this->server->register(get_class($this).'.'.$action);
			}
			$this->server->service($HTTP_RAW_POST_DATA);
		}

		function error() {
			echo 'error';
		}

		function routeError() {
			echo 'routeError';
		}
	}
?>
