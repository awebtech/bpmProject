<?php

/**
 *  TemplateObject class
 *
 * @author Ignacio de Soto
 */
class TemplateObject extends BaseTemplateObject {

	/**
	 * Returns the Template
	 *
	 * @return Template
	 */
	function getTemplate() {
		return Templates::findById($this->getTemplateId());
	}
	
	function getObject() {
		return get_object_by_manager_and_id($this->getObjectId(), $this->getObjectManager());
	}
	
	function setTemplate($template) {
		if ($template instanceof COTemplate) {
			$this->setTemplateId($template->getId());
		}
	}
	
	function setObject($object) {
		if ($object instanceof DataObject) {
			$this->setObjectId($object->getId());
			$this->setObjectManager(get_class($object->manager()));
		}
	}
} // TemplateObject

?>