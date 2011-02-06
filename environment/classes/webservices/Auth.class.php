<?php

	/**
	 * Description of Auth
	 *
	 * @author awebtech
	 */
	class Auth extends WebServiceObject {
		static function Init() {
			self::$operations = array(
				'Login' => array(
					'in' => array(
						'login' => 'xsd:string',
						'password' => 'xsd:string',
					),
					'out' => array(
						'token' => 'xsd:string'
					),
				),
			);

			self::$requireAuth = false;			
		}


		function Login($login, $password) {
			$login = trim($login);
			$password = trim($password);

			$user = Users::getByUsername($login, owner_company());
			
			if(!($user instanceof User) || !$user->isValidPassword($password)) {
				throw new WebServiceFault('Client', 'Access denied');
			}

			$result = array();
			$result[Cookie::getPrefix().'id'] = $user->getId();
			$result[Cookie::getPrefix().'token'] = $user->getTwistedToken();

			$result = serialize($result);
			$result = base64_encode($result);

			return $result;
		}
	}
?>