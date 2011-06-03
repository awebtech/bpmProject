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
			
			// We will search for the target workspace inside the root workspace
			$root_ws_name = Mapping::Get('GroupToWorkspace', $group->getName());
			$ws_name = Mapping::Get('WorkflowToWorkspace', $wso->workflow_stage);
			
			$target_ws_name = $root_ws_name.'/'.$ws_name;
			
			$workspace = Projects::getProjectFromPath($target_ws_name);
			
			if (!($workspace instanceof Project)) {
				$return->error = 'Workspace "'.$target_ws_name.'" not found';
				return $return;
			}
			
			$return->workspace_id = $workspace->getId();
			
			return $return;
		}
	}

?>