<?php

	/**
	 * Description of objMilestone
	 *
	 * @author master
	 */
	class objMilestone extends WebServiceObject {
		static function DefineOperations() {
			self::$operations = array(
				'Create' => array(
					'in' => array(
						'name' => 'xsd:string'
					),
					'out' => array(
						'return' => 'xsd:string'
					),
				),
				'Delete' => array(
					'in' => array(
						'name' => 'xsd:string'
					),
					'out' => array(
						'return' => 'xsd:string'
					),
				),
			);
		}

		function Create($name) {
			return "Milestone $name has been created";
		}

		function Delete($name) {
			return "Milestone $name has been deleted";
		}
	}
?>
