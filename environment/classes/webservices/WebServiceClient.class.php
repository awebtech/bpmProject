<?php
	/**
	 * Description of WebServiceClient
	 *
	 * @author awebtech
	 */
	class WebServiceClient {
		var $client = null;

		function  __construct($wsdl_url) {
			if (!class_exists('nusoap_client')) {
				require ROOT.'/public/webservices/lib/nusoap.php';
			}

			$client = new nusoap_client($wsdl_url, true);
		}

		function call($operation, $object) {
			$result = $this->client->call($operation, $object);

			if ($this->client->fault) {
				throw new Exception('soap fault');
				// print_r($result);
			} else {
				$err = $this->client->getError();
				if ($err) {
					throw new Exception('soap error');
					// $this->client->getError();
				}
			}

			return $result;
		}
	}
?>
