<?php

	/**
	 * Description of Task
	 *
	 * @author awebtech
	 */
	class Task extends WebService {
		function Create($wso) {
			$return = new stdClass();
			$return->task = null;
			$return->error = '';

			$auth_result = $this->auth($wso->auth->token);
			if (true !== $auth_result) {
				$return->error = $auth_result;
				return $return;
			}

			Env::useHelper('permissions');
			Hook::register('task');

			$_GET['c'] = 'task';
			$_GET['a'] = 'do_add_task';

			//error_log(print_r($task, true));

			$task = new TaskWso($wso->task);

			$this->executeAction($task);

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

			$task_id = TaskController::getMainObjectId();
			
			$task = ProjectTasks::findById($task_id);
			
			$wso = new TaskWso($task);
			$wso = $wso->getWsoState(true);
			
			$return->task = $wso;
			
			return $return;
		}
	}

	function task_object_validate($object, &$errors) {
		if ($object instanceof ProjectTask && !empty($errors)) {
			throw new WebServiceFault('Client', implode("\n", $errors));
		}
	}

?>