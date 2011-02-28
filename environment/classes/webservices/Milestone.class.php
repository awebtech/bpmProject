<?php

	/**
	 * Description of Milestone
	 *
	 * @author awebtech
	 */
	class Milestone extends WebService {
		static function init() {
			self::$operations = array(
				'Create' => array(
					'in' => array(
						'milestone' => 'tns:Milestone'
					),
					'out' => array(
						'return' => 'xsd:int'
					),
					'complexTypes' => array(
						'KeyValue', 'MilestoneGeneric', 'CustomProperties', 'Milestone',
					),
				),
				'Update' => array(
					'in' => array(
						'milestone' => 'tns:Milestone'
					),
					'out' => array(
						'return' => 'xsd:boolean'
					),
					'complexTypes' => array(
						'KeyValue', 'MilestoneGeneric', 'CustomProperties', 'Milestone',
					),
				),
			);
		}
	}
	
	class Create extends WebServiceOperationWithAuth {
		function  __construct($args) {
			parent::__construct($args);

			Env::useHelper('permissions');
			Hook::register("milestone");
		}

		function execute($milestone) {			
			$_GET['c'] = 'milestone';
			$_GET['a'] = 'add';			

			if (!empty($milestone['object_custom_properties'])) {
				$milestone['object_custom_properties'] = WebServiceComplexType::ToAssocArray($milestone['object_custom_properties']);
			}

			$_POST = $milestone;

			self::ExecuteAction(request_controller(), request_action());

			$error = flash_get('error');

			if (!empty($error)) {
				throw new WebServiceFault('Client', $error);
			}

			return MilestoneController::getMainObjectId();
		}
	}

	class Update extends WebServiceOperationWithAuth {
		function  __construct($args) {
			parent::__construct($args);

			Env::useHelper('permissions');
			Hook::register("milestone");
		}

		function execute($milestone) {
			$_GET['c'] = 'milestone';
			$_GET['a'] = 'edit';
			$_GET['id'] = $milestone['id'];			

			if (!empty($milestone['object_custom_properties'])) {
				$milestone['object_custom_properties'] = WebServiceComplexType::ToAssocArray($milestone['object_custom_properties']);
			}

			$_POST = $milestone;

			self::ExecuteAction(request_controller(), request_action());

			$error = flash_get('error');

			if (!empty($error)) {
				throw new WebServiceFault('Client', $error);
			}

			return true;
		}
	}

	function milestone_object_validate($object, &$errors) {
		if ($object instanceof ProjectMilestone && !empty($errors)) {
			throw new WebServiceFault('Client', implode("\n", $errors));
		}
	}

?>