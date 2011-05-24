<?php
	/**
	 * Description of WebServiceObject
	 *
	 * @author awebtech
	 */
	class WebServiceObject {
		private $object_type = '';
		protected $data = null;
		protected $converted = null;
		protected $data_template = null;

		function  __construct($object_type, $data) {
			$this->object_type = $object_type;
			$this->data = $data;
		}

		private function getCurrentToken() {
			$user = CompanyWebsite::instance()->getLoggedUser();			
			$token = array();
			$token[Cookie::getPrefix().'id'] = $user->getId();
			$token[Cookie::getPrefix().'token'] = $user->getTwistedToken();

			$token = serialize($token);
			$token = base64_encode($token);

			return $token;
		}

		/*
		 * Convert input data to state suitable for transfer via Web service
		 */
		private function convertToWso() {
			$this->converted = new stdClass();
			foreach ($this->data_template as $name => $value) {
				$search_name = strtolower($name);
				if (array_key_exists($search_name, $this->data)) {
					$value = $this->data[$search_name];
				} else {
					$this->converted->$name = '';
					continue;
				}
				if (!is_array($value)) {
					$this->converted->$name = $value;
				} else {
					switch ($search_name) {
						case 'object_custom_properties':
							foreach ($value as $k => $v) {
								$cp = CustomProperties::getCustomProperty($k);
								$new_name = Mapping::Get(array($this->object_type, 'object_custom_properties'), $cp->getName());								
								$this->converted->$new_name = $v;
							}
						break;
						default:
							foreach ($this->data_template[$name] as $k => $v) {
								if (array_key_exists($k, $this->data[$name])) {
									$this->converted->$k = $this->data[$name][$k];
								} else {
									$this->converted->$k = '';
								}
							}
						break;
					}
				}
			}
		}

		/*
		 * Convert input data backward from WSO state to Feng Office internal state
		 */
		private function convertToNormal() {
			foreach ($this->data_template as $name => $value) {
				if (!is_array($value)) {
					$this->converted[$name] = $this->data->$name;
				} else if ($name != 'object_custom_properties') {
					foreach ($value as $k => $v) {
						$this->converted[$name][$k] = $this->data->$k;
					}
				} else { // if ($name == 'object_custom_properties')
					foreach ($value as $k => $v) {
						$new_name = Mapping::Get(array($this->object_type, 'object_custom_properties'), $k, false);
						//error_log('CustomProperties::getCustomPropertyByName($this->object_type, $new_name):'.'CustomProperties::getCustomPropertyByName('.$this->object_type.', '.$new_name.')');
						$cp = CustomProperties::getCustomPropertyByName($this->object_type, $new_name);
						$this->converted[$name][$cp->getId()] = $this->data->$k;
					}
				}
			}
		}

		// Create object
		function getWsoState($complexType = '') {
			$this->convertToWso();
			$request = new stdClass();
			if (!empty($complexType)) {
				$request->$complexType = $this->converted;
			} else {
				$request = $this->converted;				
			}
			$request->token = $this->getCurrentToken();

			return $request;
		}

		function getNormalState() {
			$this->convertToNormal();

			return $this->converted;
		}

		function getObject() {
			return $object;
		}
	}

?>
