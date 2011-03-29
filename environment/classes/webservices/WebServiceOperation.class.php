<?php

	/**
	 * Description of WebServiceOperation
	 *
	 * @author awebtech
	 */
	abstract class WebServiceOperation {
		//abstract function execute($args); // Commented out, but true. Commented out, because the method signature can not have a variable number of parameters.

		/**
		 * Contruct controller and execute specific action
		 * Cloned from Env::executeAction
		 *
		 * @access public
		 * @param string $controller_name
		 * @param string $action
		 * @return null
		 */
		static function ExecuteAction($controller_name, $action) {
			$max_users = config_option('max_users');
			if ($max_users && Users::count() > $max_users) {
			   echo lang("error").": ".lang("maximum number of users exceeded error");
			   return;
			}

			if (isset($_GET['active_project']) && logged_user() instanceof User) {
				set_user_config_option('lastAccessedWorkspace', $_GET['active_project'], logged_user()->getId());
			}

			Env::useController($controller_name);

			$controller_class = Env::getControllerClass($controller_name);
			if(!class_exists($controller_class, false)) {
				throw new ControllerDnxError($controller_name);
			} // if

			$controller = new $controller_class();
			if(!instance_of($controller, 'Controller')) {
				throw new ControllerDnxError($controller_name);
			} // if

			$controller->setAutoRender(false);

			error_log('action: '.$action);
			return $controller->execute($action);
		}
	}

?>
