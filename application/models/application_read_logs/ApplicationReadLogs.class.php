<?php

/**
 * Application logs manager class
 *
 * @author Ilija Studen <ilija.studen@gmail.com>
 */
class ApplicationReadLogs extends BaseApplicationReadLogs {

	const ACTION_READ         = 'read';
	const ACTION_DOWNLOAD     = 'download';
		
	public static function getWorkspaceString($ids = '?') {
		if (is_array($ids)) $ids = implode(",", $ids);
		return " `id` IN (SELECT `object_id` FROM `" . TABLE_PREFIX . "workspace_objects` WHERE `object_manager` = 'ApplicationReadLogs' AND `workspace_id` IN ($ids))";
	}
	
	/**
	 * Create new log entry and return it
	 *
	 * Delete actions are automatically marked as silent if $is_silent value is not provided (not NULL)
	 *
	 * @param ApplicationDataObject $object
	 * @param Project $project
	 * @param DataManager $manager
	 * @param boolean $save Save log object before you save it
	 * @return ApplicationReadLog
	 */
	static function createLog(ApplicationDataObject $object, $workspaces, $action = null, $is_private = false, $is_silent = null, $save = true, $log_data = '') {
		if(is_null($action)) {
			$action = self::ACTION_READ;
		} // if
		if(!self::isValidAction($action)) {
			throw new Error("'$action' is not valid log action");
		} // if

		/*if(is_null($is_silent)) {
			$is_silent = $action == self::ACTION_READ;
		} else {
			$is_silent = (boolean) $is_silent;
		} // if
		*/

		try {
			Notifier::notifyAction($object, $action, $log_data);
		} catch (Exception $ex) {
			
		}
		
		$manager = $object->manager();
		if(!($manager instanceof DataManager)) {
			throw new Error('Invalid object manager');
		} // if

		$log = new ApplicationReadLog();

		if (logged_user() instanceof User) {
			$log->setTakenById(logged_user()->getId());
		} else {
			$log->setTakenById(0);
		}
		$log->setRelObjectId($object->getObjectId());
//		$log->setObjectName($object->getObjectName());
		$log->setRelObjectManager(get_class($manager));
		$log->setAction($action);
//		$log->setIsPrivate($is_private);
//		$log->setIsSilent($is_silent);
		$log->setLogData($log_data);
		
		if($save) {
			$log->save();
		} // if
		
		// Update is private for this object
		/*if($object instanceof ProjectDataObject) {
			ApplicationReadLogs::setIsPrivateForObject($object);
		} // if*/

		if ($save) {
			if ($workspaces instanceof Project) {
				$wo = new WorkspaceObject();
				$wo->setObject($log);
				$wo->setWorkspace($workspaces);
				$wo->save();
			} else if (is_array($workspaces)) {
				foreach ($workspaces as $w) {
					if ($w instanceof Project) {
						$wo = new WorkspaceObject();
						$wo->setObject($log);
						$wo->setWorkspace($w);
						$wo->save();
					}
				}
			}
		}

		return $log;
	} // createLog

	/**
	 * Update is_private flag value for all previous related log entries related with specific object
	 *
	 * This method is called whenever we need to add new log entry. It will keep old log entries related to that specific
	 * object with current is_private flag value by updating all of the log entries to new value.
	 *
	 * @param ProjectDataObject $object
	 * @return boolean
	 */
	static function setIsPrivateForObject(ProjectDataObject $object) {
		return DB::execute('UPDATE ' . ApplicationReadLogs::instance()->getTableName(true) . ' SET `is_private` = ?  WHERE `rel_object_id` = ?  AND `rel_object_manager` = ?',
		$object->isPrivate(),
		$object->getObjectId(),
		get_class($object->manager()
		)); // execute
	} // setIsPrivateForObject

	/**
	 * Mass set is_private for a given type. If $ids is present limit update only to object with given ID-s
	 *
	 * @param boolean $is_private
	 * @param string $type
	 * @parma array $ids
	 * @return boolean
	 */
	static function setIsPrivateForType($is_private, $type, $ids = null) {
		$limit_ids = null;
		if(is_array($ids)) {
			$limit_ids = array();
			foreach($ids as $id) {
				$limit_ids[] = DB::escape($id);
			} // if

			$limit_ids = count($limit_ids) > 0 ? implode(',', $limit_ids) : null;
		} // if

		$sql = DB::prepareString('UPDATE ' . ApplicationReadLogs::instance()->getTableName(true) . ' SET `is_private` = ?  WHERE `rel_object_manager` = ?', array($is_private, $type));
		if($limit_ids !== null) {
			$sql .= " AND `rel_object_id` IN ($limit_ids)";
		} // if

		return DB::execute($sql);
	} // setIsPrivateForType

	/**
	 * Return entries related to specific project
	 *
	 * If $include_private is set to true private entries will be included in result. If $include_silent is set to true
	 * logs marked as silent will also be included. $limit and $offset are there to control the range of the result,
	 * usually we don't want to pull the entire log but just the few most recent entries. If NULL they will be ignored
	 *
	 * @param Project $project
	 * @param boolean $include_private
	 * @param boolean $include_silent
	 * @param integer $limit
	 * @param integer $offset
	 * @return array
	 */
	static function getProjectLogs(Project $project, $include_private = false, $include_silent = false, $limit = null, $offset = null) {
		$private_filter = $include_private ? 1 : 0;
		$silent_filter = $include_silent ? 1 : 0;

		return self::findAll(array(
        'conditions' => array('`is_private` <= ? AND `is_silent` <= ? AND `project_id` = (?)', $private_filter, $silent_filter, $project->getId()),
        'order' => '`created_on` DESC',
        'limit' => $limit,
        'offset' => $offset,
		)); // findAll
	} // getProjectLogs

	/**
	 * Return overall (for dashboard or RSS)
	 *
	 * This function will return array of application logs that match the function arguments. Entries can be filtered by
	 * type (prvivate, silent), projects (if $project_ids is array, if NULL project ID is ignored). Result set can be
	 * also limited using $limit and $offset params
	 *
	 * @param boolean $include_private
	 * @param boolean $include_silent
	 * @param mixed $project_ids
	 * @param integer $limit
	 * @param integer $offset
	 * @return array
	 */
	static function getOverallLogs($include_private = false, $include_silent = false, $project_ids = null, $limit = null, $offset = null, $user_id = null) {
		$private_filter = $include_private ? 1 : 0;
		$silent_filter = $include_silent ? 1 : 0;

		$userCond = '';
		if ($user_id)
		$userCond = " AND `taken_by_id` = " . $user_id;

		if(!is_null($project_ids)) {
			$conditions = array('`is_private` <= ? AND `is_silent` <= ? AND '.self::getWorkspaceString($project_ids) . $userCond, $private_filter, $silent_filter);
		} else {
			$conditions = array('`is_private` <= ? AND `is_silent` <= ?' . $userCond, $private_filter, $silent_filter);
		} // if

		return self::findAll(array(
			'conditions' => $conditions,
			'order' => '`created_on` DESC',
			'limit' => $limit,
			'offset' => $offset,
		)); // findAll
	} // getOverallLogs

	/**
	 * Clear all logs related with specific project
	 *
	 * @param Project $project
	 * @return boolean
	 */
	static function clearByProject(Project $project) {
		return self::delete(array(self::getWorkspaceString(), $project->getId()));
	} // clearByProject

	/**
	 * Check if specific action is valid
	 *
	 * @param string $action
	 * @return boolean
	 */
	static function isValidAction($action) {
		static $valid_actions = null;

		if(!is_array($valid_actions)) {
			$valid_actions = array(
			self::ACTION_READ,
			self::ACTION_DOWNLOAD
			); // array
		} // if

		return in_array($action, $valid_actions);
	} // isValidAction

	/**
	 * Return entries related to specific object
	 *
	 * If $include_private is set to true private entries will be included in result. If $include_silent is set to true
	 * logs marked as silent will also be included. $limit and $offset are there to control the range of the result,
	 * usually we don't want to pull the entire log but just the few most recent entries. If NULL they will be ignored
	 *
	 * @param ApplicationDataObject $object
	 * @param boolean $include_private
	 * @param boolean $include_silent
	 * @param integer $limit
	 * @param integer $offset
	 * @return array
	 */
	static function getObjectLogs($object, $include_private = false, $include_silent = false, $limit = null, $offset = null) {
		$private_filter = $include_private ? 1 : 0;
		$silent_filter = $include_silent ? 1 : 0;

//		return self::findAll(array(
//        'conditions' => array('`is_private` <= ? AND `is_silent` <= ? AND `rel_object_id` = (?) AND `rel_object_manager` = (?)', $private_filter, $silent_filter, $object->getId(),get_class($object->manager())),
//        'order' => '`created_on` DESC',
//        'limit' => $limit,
//        'offset' => $offset,
//		)); // findAll

		return self::findAll(array(
        'conditions' => array('`rel_object_id` = (?) AND `rel_object_manager` = (?)', $object->getId(),get_class($object->manager())),
        'order' => '`created_on` DESC',
        'limit' => $limit,
        'offset' => $offset,
		)); // findAll
	} // getObjectLogs

} // ApplicationReadLogs

?>