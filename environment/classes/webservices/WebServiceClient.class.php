<?php
	/**
	 * Description of WebServiceClient
	 *
	 * @author awebtech
	 */
	class WebServiceClient {
		private $client = null;

		function  __construct($service_name) {			
			//$this->wsdl_base = ROOT_URL.'/public/webservices/';
			$wsdl_base = '';//'http://devnmark:8080/ode/processes/FO_ConstructionProduction/Diagramms/FO_Construction/Construction_FO/';
			$wsdl_uri = $wsdl_base.$service_name;
			$this->client = new SoapClient($wsdl_uri);

		}

		function __call($method, $args) {
			try {
				$result = $this->client->$method($args[0]);
			} catch (SoapFault $e) {
				throw new Exception('SOAP error: '.$e->getMessage());
			}

			return $result;
		}
	}
?>