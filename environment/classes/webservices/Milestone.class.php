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
						'MilestoneGeneric', 'CustomProperties', 'Milestone',
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

			$_POST = $milestone;

			self::ExecuteAction(request_controller(), request_action());

			$error = flash_get('error');

			if (!empty($error)) {
				throw new WebServiceFault('Client', $error);
			}

			return MilestoneController::getMainObjectId();
		}
	}

	function milestone_object_validate($object, &$errors) {
		if ($object instanceof ProjectMilestone && !empty($errors)) {
			throw new WebServiceFault('Client', implode("\n", $errors));
		}
	}

?>