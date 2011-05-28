<?php

	/**
	 * Description of WebService
	 *
	 * @author awebtech
	 */
	abstract class WebService {
		private static $hookError = '';

		protected function auth($token) {
			if (empty($token)) {
				//throw new WebServiceFault('SF-0002', 'Authorization failed, empty token');
				return 'Authorization failed, empty token';
			}

			$credentials = unserialize(base64_decode($token));
			if (!empty($credentials) && is_array($credentials)) {
				foreach ($credentials as $k => $v) {
					$_COOKIE[$k] = $v;
				}
				CompanyWebsite::instance()->init();
			}

			$user = CompanyWebsite::instance()->getLoggedUser();
			if (!$user instanceof User) {
				//throw new WebServiceFault('SF-0003', 'Authorization failed');
				return 'Authorization failed';
			}

			return true;
		}

		/**
		 * Construct controller and execute specific action
		 * Cloned from Env::executeAction
		 *
		 * @access public
		 * @param WebServiceObject $wso
		 * @return action execution result
		 */
		protected function executeAction($wso) {
			$_POST = $wso->getNormalState();

			$controller_name = request_controller();
			$action = request_action();

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

			//error_log('action: '.$action);
			return $controller->execute($action);
		}

		public static function setHookError($error) {
			self::$hookError = $error;
		}

		protected function wasHookError() {
			return !empty(self::$hookError);
		}

		protected function getHookError() {
			return self::$hookError;
		}
	}
?>
