<?php
	/**
	 * Description of MilestoneWso
	 *
	 * @author awebtech
	 */
	class MilestoneWso extends WebServiceObject {
		protected $complexType = 'milestone';
		
		function  __construct($data) {
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
				'url' => '',
			);

			parent::__construct('ProjectMilestones', $data);
		}		
		
		protected function convertToWsoFromObject() {
			$this->converted = new stdClass();
			$this->converted->id = $this->data->getId();
			$this->converted->name = $this->data->getName();
			$this->converted->tags = implode(',', $this->data->getTagNames());
			$this->converted->description = $this->data->getDescription();
			$this->converted->assigned_to = $this->data->getAssignedToCompanyId().':'.$this->data->getAssignedToUserId();
			$this->converted->send_notification = ''; // Not necessary when the obect has already been created
			$this->converted->is_urgent = $this->data->getIsUrgent();
			$this->converted->due_date_value = $this->data->getDueDate()->toMySQL();
			$this->converted->is_template = $this->data->getIsTemplate();
			$this->converted->ws_ids = $this->data->getWorkspacesIdsCSV();
			$this->converted->taskFormAssignedToCombo = '';
			foreach ($this->data_template['object_custom_properties'] as $cp_name => $empty) {
				$cp_mapped_name = Mapping::Get(array($this->object_type, 'object_custom_properties'), $cp_name, false);
				
				$cp = CustomProperties::getCustomPropertyByName($this->object_type,  $cp_mapped_name);
				$cp_value = CustomPropertyValues::getCustomPropertyValue($this->converted->id, $cp->getId());
				$this->converted->$cp_name = $cp_value->getValue();
			}
			$this->converted->updatedon = '';
			$this->converted->url = $this->data->getObjectUrl();
		}
	}
?>
