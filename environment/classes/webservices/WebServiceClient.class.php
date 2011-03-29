<?php
	/**
	 * Description of WebServiceClient
	 *
	 * @author awebtech
	 */
	class WebServiceClient {
		private $client = null;
		private $wsdl_base = '';

		function  __construct($service_name) {
			if (!class_exists('nusoap_client')) {
				require ROOT.'/public/webservices/lib/nusoap.php';
			}

			//$this->wsdl_base = ROOT_URL.'/public/webservices/';
			$this->wsdl_base = 'http://devnmark:8080/ode/processes/FO_ConstructionProduction/Diagramms/FO_Construction/Construction_FO/';
			$this->client = new nusoap_client($this->wsdl_base.$service_name, true);
		}

		function call($operation, $object) {
			$result = $this->client->call($operation, $object);

			if ($this->client->fault) {
				//throw new Exception('soap fault: '.print_r($result, true));
				//print_r($result);
				$message = !empty($result['faultstring']) ? $result['faultstring'] : 'SOAP fault';
				throw new Exception(lang($message));
			} else {
				$err = $this->client->getError();
				if ($err) {
					throw new Exception('soap error: '.$err);
					// $this->client->getError();
				}
			}

			return $result;
		}
	}
?>