<?php
	/**
	 * Description of Milestone
	 *
	 * @author awebtech
	 */
	class Milestone extends WebService {
		function Create($wso) {
			$return = new stdClass();
			$return->milestone_id = 0;
			$return->error = '';

			$auth_result = $this->auth($wso->token);
			if (true !== $auth_result) {
				$return->error = $auth_result;
				return $return;
			}

			Env::useHelper('permissions');
			Hook::register('milestone');

			$_GET['c'] = 'milestone';
			$_GET['a'] = 'do_add';

			//error_log(print_r($milestone, true));

			$milestone = new MilestoneWso($wso->milestone);

			$this->executeAction($milestone);

			$error = flash_get('error');

			if (!empty($error)) {
				//throw new WebServiceFault('Client', $error);
				$return->error = $error;
				return $return;
			}

			if ($this->wasHookError()) {
				$return->error = $this->getHookError();
				return $return;
			}

			$return->milestone_id = MilestoneController::getMainObjectId();
			
			return $return;
		}

		/*

		class Update extends WebServiceOperationWithAuth {
		function  __construct($args) {
			parent::__construct($args);

			Env::useHelper('permissions');
			Hook::register('milestone');
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

		*/
	}

	// This function is here to process FengOffice validation hook and don't miss the errors
	function milestone_object_validate($object, &$errors) {
		if ($object instanceof ProjectMilestone && !empty($errors)) {
			//throw new WebServiceFault('Client', implode("\n", $errors));
			Milestone::setHookError(implode("\n", $errors));
		}
	}
?>
