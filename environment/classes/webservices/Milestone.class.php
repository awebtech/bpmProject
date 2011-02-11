<?php

	/**
	 * Description of Milestone
	 *
	 * @author awebtech
	 */
	class Milestone extends WebService {
		static function init() {
			self::$operations = array(
				'Create' => array(
					'in' => array(
						'milestone' => 'tns:Milestone'
					),
					'out' => array(
						'return' => 'xsd:string'
					),
					'complexTypes' => array(
						'MilestoneGeneric', 'CustomProperties', 'Milestone',
					),
				),
				'Delete' => array(
					'in' => array(
						'name' => 'xsd:string'
					),
					'out' => array(
						'return' => 'xsd:string'
					),
				),
			);
		}
	}
	
	class Create extends WebServiceOperation {
		function execute($milestone) {
			return print_r($milestone, true);

			if (logged_user()->isGuest()) {
				throw new WebServiceFault('Client', lang('no access permissions'));
			}

			if(!ProjectMilestone::canAdd(logged_user(), active_or_personal_project())) {
				throw new WebServiceFault('Client', lang('no access permissions'));
			}

			$milestone_data = array_var($_POST, 'milestone');
			$user = Users::getByUsername('root', owner_company());
			CompanyWebsite::instance()->logUserIn($user, false);

			$milestone = new ProjectMilestone();

			$milestone_data['due_date'] = getDateValue(array_var($milestone_data, 'due_date_value'),DateTimeValueLib::now()->beginningOfDay());
			$assigned_to = explode(':', array_var($milestone_data, 'assigned_to', ''));
			$milestone->setIsPrivate(false); //Mandatory to set
			$milestone->setFromAttributes($milestone_data);
			$urgent = array_var($milestone_data, 'is_urgent') == 'checked';
			$milestone->setIsUrgent($urgent);

			$project = Projects::findById(array_var($_POST, 'ws_ids', 0));
			if (!$project instanceof Project && !ProjectMilestone::canAdd(logged_user(), $project)) {
				throw new WebServiceFault('Client', lang('no access permissions'));
			}

			$milestone->setAssignedToCompanyId(array_var($assigned_to, 0, 0));
			$milestone->setAssignedToUserId(array_var($assigned_to, 1, 0));

			try {
				DB::beginWork();

				$milestone->save();
				$milestone->setTagsFromCSV(array_var($milestone_data, 'tags'));
				$object_controller = new ObjectController();
				$object_controller->add_to_workspaces($milestone);
				//$object_controller->link_to_new_object($milestone);
				//$object_controller->add_subscribers($milestone);
				$object_controller->add_custom_properties($milestone);
				//$object_controller->add_reminders($milestone);

				ApplicationLogs::createLog($milestone, $milestone->getWorkspaces(), ApplicationLogs::ACTION_ADD);

				DB::commit();
			} catch(Exception $e) {
				DB::rollback();
				throw $e;
			}
		}
	}

	function Delete($name) {
		return "Milestone $name has been deleted";
	}
?>