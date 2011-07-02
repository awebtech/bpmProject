<?php
	/**
	 * Description of TaskWso
	 *
	 * @author awebtech
	 */
	class TaskWso extends WebServiceObject {
		function  __construct($data) {
			$this->data_template = array(
				'id' => 0,
				'task' => array(
					'title' => '',
					'tags' => '',
					'text' => '',
					'assigned_to' => '',
					'is_template' => '',
				),
				'ws_ids' => 0,
				'taskFormAssignedToCombo' => '',
				'object_custom_properties' => array(
						'task_critical_date' => '',
						'main_assigned_to' => '',
				),
				'updatedon' => '',
			);

			parent::__construct('ProjectMilestones', $data);
		}
	}
?>
