<?php

	/**
	 * Description of Milestone
	 *
	 * @author awebtech
	 */
	class Milestone extends WebServiceObject {
		static function Init() {
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