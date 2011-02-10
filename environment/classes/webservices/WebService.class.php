<?php

	/**
	 * Description of WebService
	 *
	 * @author awebtech
	 */
	class WebService {
		private $server = null;
		private $wsClass = '';

		function  __construct($ws_name) {
			$this->server = new soap_server();
			$wsClass = $ws_name;
			$this->wsClass = $wsClass;

			$this->server->configureWSDL($ws_name, 'urn:'.$ws_name);

			$wsClass::Init();
		}

		function registerOperations() {			
			$wsClass = $this->wsClass;

			// It is necessary to get request headers (for authorization) before operation execution
			$HTTP_RAW_POST_DATA = file_get_contents("php://input");
			$headers_parser = new soap_server();
			$headers_parser->parse_request($HTTP_RAW_POST_DATA);
			$soap_action = $headers_parser->SOAPAction; // actually SOAPAction is private, but I do not see any other fast and reliable solution

			// Authorization is not necessary if soap action is not specified
			// nusoap will return WSDL if action is not specified
			if ($wsClass::IsRequireAuth() && !empty($soap_action)) {
				$soap_header = $headers_parser->requestHeader;
				unset($HTTP_RAW_POST_DATA);
				unset($headers_parser);

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
					throw new WebServiceFault('Client', 'Access denied');
				}
			}

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

		function fault($fault_code, $fault_string) {
			$this->server->fault($fault_code, $fault_string);
			$this->server->send_response(); // actually send_response() is private, but I do not see any other fast and reliable solution
		}
	}
?>
