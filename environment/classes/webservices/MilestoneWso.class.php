<?php
	/**
	 * Description of MilestoneWso
	 *
	 * @author awebtech
	 */
	class MilestoneWso extends WebServiceObject {
		function  __construct($object_type, $data) {
			$this->data_template = array(
				'id' => 0,
				'milestone' => array(
					'name' => '',
					'tags' => '',
					'description' => '',
					'assigned_to' => '',
					'send_notification' => '',
					'is_urgent' => '',
					'due_date_value' => '',
					'is_template' => '',
				),
				'ws_ids' => 0,
				'taskFormAssignedToCombo' => '',
				'object_custom_properties' => array(
						'start_date_value' => '',
						'critical_date_value' => '',
				),
				'updatedon' => '',
			);

			parent::__construct($object_type, $data);
		}
	}
?>
