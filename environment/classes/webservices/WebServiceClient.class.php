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
			//$wsdl_base = '';//'http://devnmark:8080/ode/processes/FO_ConstructionProduction/Diagramms/FO_Construction/Construction_FO/';
			$wsdl_base = ROOT_URL.'/public/webservices/wsdl/intalio';
			$wsdl_uri = $wsdl_base.'/'.$service_name.'.wsdl';
			$this->client = new SoapClient($wsdl_uri, array('cache_wsdl' => WSDL_CACHE_NONE));

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
