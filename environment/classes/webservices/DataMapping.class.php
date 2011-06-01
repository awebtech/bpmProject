<?php
	/**
	 * Description of DataMapping
	 *
	 * @author awebtech
	 */
	class DataMapping extends WebService {
		function GetManagerByDepartment($wso) {
			$return = new stdClass();
			$return->user_id = 0;
			$return->error = '';

			$auth_result = $this->auth($wso->token);
			if (true !== $auth_result) {
				$return->error = $auth_result;
				return $return;
			}

			$group = Groups::getGroupById($wso->department_id);
			
			if (!($group instanceof Group)) {
				$return->error = 'Group not found';
				return $return;
			}
			
			$return->user_id = $group->getManager();
			
			return $return;
		}

		function GetWorkspaceByDepartmentAndWorkflow($wso) {
			$return = new stdClass();
			$return->workspace_id = 0;
			$return->error = '';

			$auth_result = $this->auth($wso->token);
			if (true !== $auth_result) {
				$return->error = $auth_result;
				return $return;
			}
			
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
