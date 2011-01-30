<?php

	/**
	 * Description of WebService
	 *
	 * @author master
	 */
	class WebService {
		private $server = null;
		private $wsClass = '';

		function  __construct($ws_name) {
			$this->server = new soap_server();
			$wsClass = 'obj'.$ws_name;
			$this->wsClass = $wsClass;

			$this->server->configureWSDL($ws_name, 'urn:'.$ws_name);

			$wsClass::DefineOperations();
		}

		function registerOperations() {
			$wsClass = $this->wsClass;

			$operations = $wsClass::GetOperations();
			if (empty($operations)) {
				throw new Exception('Empty operations');
			}
			foreach ($operations as $op_name => $op_settings) {
				$this->server->register($wsClass.'.'.$op_name, $op_settings['in'], $op_settings['out']);
			}
		}

		function processRequest() {
			$HTTP_RAW_POST_DATA = file_get_contents("php://input");

			$this->server->service($HTTP_RAW_POST_DATA);			
		}
	}
?>
