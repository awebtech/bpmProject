<?php

	/**
	 * Return id of the group associated with object subtype
	 *
	 * @param int $os_id Object subtype id
	 * @return int
	 */
	function get_group_by_object_subtype($os_id) {
		$os = ProjectCoTypes::findById($os_id);
		$group_name = Mapping::Get('ObjectSubtypeToGroup', $os->getName());
		$group = Groups::GetGroupByName($group_name);
		
		return $group->getId();
	} // get_group_by_object_subtype
		
?>
