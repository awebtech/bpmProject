<?php

	/**
	 * Description of WebService
	 *
	 * @author awebtech
	 */
	abstract class WebService {
		static protected $operations = array();
		static private $complexTypes = array();

		abstract static function init();

		static function register($server) {
			$class = get_called_class();
			$class::init();
			if (!empty(self::$operations) && is_array(self::$operations)) {
				foreach (self::$operations as $op_name => $op_settings) {
					if (!empty($op_settings['complexTypes'])) {
						foreach ($op_settings['complexTypes'] as $complex_type) {
							if (in_array($complex_type, self::$complexTypes)) {
								continue;
							}
							//error_log('adding ct: '.print_r(WebServiceComplexType::Get($complex_type), true));
							call_user_func_array(array($server->wsdl, 'addComplexType'), WebServiceComplexType::Get($complex_type));
							self::$complexTypes[] = $complex_type;
						}
					}
					$server->register($class.'.'.$op_name, $op_settings['in'], $op_settings['out']);					
				}
			}
		}		

		function __call($name, $arguments) {
			//error_log('__call name: '.print_r($name, true));
			//error_log('__call args: '.print_r($arguments, true));
			$operation = new $name($arguments);
			return call_user_func_array(array($operation, 'execute'), $arguments);
		}
	}
?>
