<?php

	/**
	 * Description of Milestone
	 *
	 * @author awebtech
	 */
	class Milestone extends WebServiceObject {
		static function Init() {
			self::$operations = array(
				'Create' => array(
					'in' => array(
						'name' => 'xsd:string'
					),
					'out' => array(
						'return' => 'xsd:string'
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

		function Create($name) {

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
			);

			$_POST['milestone'] = array(
				'name' => 'Совсем новый проект',
				'tags' => 'тэг1',
				'description' => 'Описалово',
				'assigned_to' => '1:1',
				'send_notification' => 'checked',
				'is_urgent' => 'checked',
				'due_date_value' => '30/01/2011',
			);

			$_POST['ws_ids'] = 1;
			$_POST['taskFormAssignedToCombo'] = 'Me';

			$_POST['object_custom_properties'] = array(
					1 => '30/01/2011',
			);

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

		function Delete($name) {
			return "Milestone $name has been deleted";
		}
	}
?>