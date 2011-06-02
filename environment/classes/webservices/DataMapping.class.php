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
			
			$group = Groups::getGroupById($wso->department_id);
			if (!($group instanceof Group)) {
				$return->error = 'Group not found';
				return $return;
			}
			
			// We will search for the goal workspace inside the root workspace
			$root_ws_name = Mapping::Get('GroupToWorkspace', $group->getName());
			$ws_name = Mapping::Get('WorkflowToWorkspace', $wso->workflow_stage);
						
			$root_ws = Projects::getByName($root_ws_name);
			
			if (!($root_ws instanceof Project)) {
				$return->error = 'Group workspace not found';
				return $return;
			}
			
			return $return;
		}
	}

?>