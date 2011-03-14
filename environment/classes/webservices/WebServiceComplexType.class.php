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
						'wsdl:arrayType' => 'tns:KeyValue[]'
					),
				),
				'xsd:string'
			),
			'KeyValue' => array(
				'KeyValue',
				'complexType',
				'struct',
				'all',
				'',
				array(
					'key' => array(
						'name' => 'key',
						'type' => 'xsd:int',
					),
					'value' => array(
						'name' => 'value',
						'type' => 'xsd:string',
					),
				)
			),
			'Milestone' => array(
				'Milestone',
				'complexType',
				'struct',
				'all',
				'',
				array(
					'id' => array(
						'name' => 'id',
						'type' => 'xsd:int',
					),
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
					'start_date_value' => array(
						'name' => 'start_date_value',
						'type' => 'xsd:string',
					),
					'due_date_value' => array(
						'name' => 'due_date_value',
						'type' => 'xsd:string',
					),
					'critical_date_value' => array(
						'name' => 'critical_date_value',
						'type' => 'xsd:string',
					),
					'is_template' => array(
						'name' => 'is_template',
						'type' => 'xsd:boolean',
					),
					'ws_ids' => array(
						'name' => 'ws_ids',
						'type' => 'xsd:string',
					),
					'taskFormAssignedToCombo' => array(
						'name' => 'taskFormAssignedToCombo',
						'type' => 'xsd:string',
					),					
					'updatedon' => array(
						'name' => 'updatedon',
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
					'id' => array(
						'name' => 'id',
						'type' => 'xsd:int',
					),
					'task_start_date' => array(
						'name' => 'task_start_date',
						'type' => 'xsd:string',
					),
					'task_due_date' => array(
						'name' => 'task_due_date',
						'type' => 'xsd:string',
					),
					'genid' => array(
						'name' => 'genid',
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
					'updatedon' => array(
						'name' => 'updatedon',
						'type' => 'xsd:string',
					),
					'token' => array(
						'name' => 'token',
						'type' => 'xsd:string',
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

		static function ToKeyValue($array) {
			$res = array();

			if (empty($array)) {
				return $res;
			}

			$ind = 0;
			foreach ($array as $key => $value) {
				$res[$ind]['key'] = $key;
				$res[$ind]['value'] = $value;
				++$ind;
			}

			return $res;
		}

		static function ToAssocArray($array) {
			$res = array();

			if (empty($array)) {
				return $res;
			}

			foreach ($array as $element) {
				$res[$element['key']] = $element['value'];
			}

			return $res;
		}
	}

?>
