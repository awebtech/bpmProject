<?php

	/**
	 * Description of Task
	 *
	 * @author awebtech
	 */
	class Task extends WebService {
		static function init() {
			self::$operations = array(
				'Create' => array(
					'in' => array(
						'task' => 'tns:Task'
					),
					'out' => array(
						'return' => 'xsd:int'
					),
					'complexTypes' => array(
						'TaskGeneric', 'CustomProperties', 'Task',
					),
				),
			);
		}
	}
	
	// TODO: custom properties indexes are lost during requests, it is necesssary to convert them into array of structs key=>value

	class Create extends WebServiceOperationWithAuth {
		function  __construct($args) {
			parent::__construct($args);

			Env::useHelper('permissions');
			Hook::register("task");
		}

		function execute($task) {
			$_GET['c'] = 'task';
			$_GET['a'] = 'add_task';

			$_POST = $task;

			self::executeAction(request_controller(), request_action());

			$error = flash_get('error');

			if (!empty($error)) {
				throw new WebServiceFault('Client', $error);
			}
			
			return TaskController::getMainObjectId();
		}
	}

	function task_object_validate($object, &$errors) {
		if ($object instanceof ProjectTask && !empty($errors)) {
			throw new WebServiceFault('Client', implode("\n", $errors));
		}
	}

?>