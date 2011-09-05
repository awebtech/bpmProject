<?php
	/**
	 * Description of TaskWso
	 *
	 * @author awebtech
	 */
	class TaskWso extends WebServiceObject {
		protected $complexType = 'task';
		protected $workflowStage = '';
		
		function  __construct($data) {
			$this->data_template = array(
				'id' => 0,
				'task' => array(
					'title' => '',
					'tags' => '',
					'milestone_id' => '',
					'object_subtype' => '',
					'priority' => '',
					'text' => '',
					'assigned_to' => '',
					'is_template' => '',
				),
				'workflow_stage' => '',
				'ws_ids' => 0,
				'genid' => '',
				'merge-changes' => '',
				'updatedon' => '',
				'task_start_date' => '',
				'task_due_date' => '',
				'taskFormAssignedToCombo' => '',
				'object_custom_properties' => array(
						'task_critical_date' => '',
						'main_assigned_to' => '',
				),
				// move below into custom properties ? what about top level = additional_supply (as) ? as->department_id for example
				'is_due_date_confirmed_by_manager' => '',
				'department_id' => '',
				'completion_percentage' => '',
				'completion_status' => '',
			);

			parent::__construct('ProjectTasks', $data);
		}
		
		protected function convertToWsoFromObject() {
			$task = $this->data;
			$converted = $this->converted;
			
			$converted = new stdClass();
			$converted->id = $task->getId();
			$converted->title = $task->getTitle();
			$converted->tags = implode(',', $task->getTagNames());
			$converted->milestone_id = $task->getMilestoneId();
			$os = ProjectCoTypes::findById($task->getObjectSubtype());
			$converted->object_subtype = Mapping::Get(array($this->object_type, 'object_subtypes'), $os->getName());
			$converted->priority = $task->getPriority();
			$converted->text = $task->getText();
			$converted->assigned_to = $task->getAssignedToCompanyId().':'.$task->getAssignedToUserId();
			$converted->is_template = $task->getIsTemplate();
			$converted->workflow_stage = ''; // filled by BPMS (Intalio)
			$converted->ws_ids = $task->getWorkspacesIdsCSV();
			$converted->genid = ''; // not necessary
			$converted->{'merge-changes'} = ''; // not necessary
			$converted->updatedon = ''; // not necessary
			$converted->task_start_date = $task->getStartDate()->toMySQL();
			$converted->task_due_date = $task->getDueDate()->toMySQL();
			$converted->taskFormAssignedToCombo = '';
			
			foreach ($this->data_template['object_custom_properties'] as $cp_name => $empty) {
				$cp_mapped_name = Mapping::Get(array($this->object_type, 'object_custom_properties'), $cp_name, false);
				
				$cp = CustomProperties::getCustomPropertyByName($this->object_type,  $cp_mapped_name);
				$cp_value = CustomPropertyValues::getCustomPropertyValue($task->id, $cp->getId());
				$converted->$cp_name = $cp_value->getValue();
			}
			
			return true;
		}
		
		protected function wsoStatePostprocess() {		
			$property = 'object_subtype';
			$value = $this->converted->$property;
			$os = ProjectCoTypes::findById($value); // object_subtype
			$this->converted->$property = Mapping::Get(array($this->object_type, 'object_subtypes'), $os->getName());
			
			$property = 'is_due_date_confirmed_by_manager';
			$cp_name = Mapping::Get(array($this->object_type, 'object_custom_properties'), $property, false);
			$cp = CustomProperties::getCustomPropertyByName($this->object_type, $cp_name);
			$this->converted->$property = $this->data['object_custom_properties'][$cp->getId()];
			
			$property = 'department_id';
			$group_name = Mapping::Get('ObjectSubtypeToGroup', $os->getName());
			$group = Groups::getGroupByName($group_name);
			$this->converted->$property = $group->getId();
			
			$property = 'additional_supply';
			$this->converted->$property = new stdClass();
			$this->converted->$property->duration = 12;
			$this->converted->$property->start_date = '2011-01-01';
			$this->converted->$property->critical_point = '2011-01-11';
			$this->converted->$property->frequency = 1;
		}
	}
?>