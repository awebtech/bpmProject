<?php

	/**
	 * Description of WebServiceComplexType
	 *
	 * @author awebtech
	 */
	class WebServiceComplexType {
		private static $complexTypes = array(
			'CustomProperties' => array(
				'CustomProperties',
				'complexType',
				'array',
				'',
				'SOAP-ENC:Array',
				array(),
				array(
					array(
						'ref' => 'SOAP-ENC:arrayType',
						'wsdl:arrayType' => 'xsd:string[]'
					),
				),
				'xsd:string'
			),
			'Milestone' => array(
				'Milestone',
				'complexType',
				'struct',
				'all',
				'',
				array(
					'milestone' => array(
						'name' => 'milestone',
						'type' => 'tns:MilestoneGeneric',
					),
					'ws_ids' => array(
						'name' => 'ws_ids',
						'type' => 'xsd:string',
					),
					'taskFormAssignedToCombo' => array(
						'name' => 'taskFormAssignedToCombo',
						'type' => 'xsd:string',
					),
					'object_custom_properties' => array(
						'name' => 'object_custom_properties',
						'type' => 'tns:CustomProperties',
					),
				)
			),
			'MilestoneGeneric' => array(
				'MilestoneGeneric',
				'complexType',
				'struct',
				'all',
				'',
				array(
					'name' => array(
						'name' => 'name',
						'type' => 'xsd:string',
					),
					'tags' => array(
						'name' => 'tags',
						'type' => 'xsd:string',
					),
					'description' => array(
						'name' => 'description',
						'type' => 'xsd:string',
					),
					'assigned_to' => array(
						'name' => 'assigned_to',
						'type' => 'xsd:string',
					),
					'send_notification' => array(
						'name' => 'send_notification',
						'type' => 'xsd:string',
					),
					'is_urgent' => array(
						'name' => 'is_urgent',
						'type' => 'xsd:string',
					),
					'due_date_value' => array(
						'name' => 'due_date_value',
						'type' => 'xsd:string',
					),
				)
			),
			'Task' => array(
				'Task',
				'complexType',
				'struct',
				'all',
				'',
				array(
					'task' => array(
						'name' => 'task',
						'type' => 'tns:TaskGeneric',
					),
					'task_start_date' => array(
						'name' => 'task_start_date',
						'type' => 'xsd:string',
					),
					'task_due_date' => array(
						'name' => 'task_due_date',
						'type' => 'xsd:string',
					),
					'ws_ids' => array(
						'name' => 'ws_ids',
						'type' => 'xsd:string',
					),
					'taskFormAssignedToCombo' => array(
						'name' => 'taskFormAssignedToCombo',
						'type' => 'xsd:string',
					),
					'object_custom_properties' => array(
						'name' => 'object_custom_properties',
						'type' => 'tns:CustomProperties',
					),
				)
			),
			'TaskGeneric' => array(
				'TaskGeneric',
				'complexType',
				'struct',
				'all',
				'',
				array(
					'title' => array(
						'name' => 'title',
						'type' => 'xsd:string',
					),
					'tags' => array(
						'name' => 'tags',
						'type' => 'xsd:string',
					),
					'milestone_id' => array(
						'name' => 'milestone_id',
						'type' => 'xsd:int',
					),
					'priority' => array(
						'name' => 'priority',
						'type' => 'xsd:int',
					),
					'object_subtype' => array(
						'name' => 'object_subtype',
						'type' => 'xsd:int',
					),
					'text' => array(
						'name' => 'text',
						'type' => 'xsd:string',
					),
					'assigned_to' => array(
						'name' => 'assigned_to',
						'type' => 'xsd:string',
					),
					'send_notification' => array(
						'name' => 'send_notification',
						'type' => 'xsd:string',
					),
				)
			),
		);

		static function Get($type_name) {
			if (!array_key_exists($type_name, self::$complexTypes)) {
				return array();
			}

			return self::$complexTypes[$type_name];
		}
	}

?>
