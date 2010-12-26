<?php

  /**
  *  LinkedObjects, generated on Wed, 26 Jul 2006 11:18:14 +0200 by 
  * DataObject generation tool
  *
  * @author Marcos Saiz <marcos.saiz@gmail.com>
  */
  class  LinkedObjects extends BaseLinkedObjects {
  
    /**
    * Return all relation objects ( LinkedObjects) for specific object
    *
    * @param ProjectDataObject $object
    * @return array
    */
    static function getRelationsByObject(ApplicationDataObject $object) {
      return self::findAll(array(
        'conditions' => array('(`rel_object_manager` = ? and `rel_object_id` = ?) or (`object_manager` = ? and `object_id` = ?)', 
        		get_class($object->manager()), $object->getObjectId(), get_class($object->manager()), $object->getObjectId()),
        'order' => '`created_on`'
      )); // findAll
    } // getRelationsByObject
    
    
    /**
    * Return linked objects by object
    *
    * @param ProjectDataObject $object
    * @param boolean $exclude_private Exclude private objects
    * @return array
    */
    static function getLinkedObjectsByObject(ApplicationDataObject $object, $exclude_private = false) {
      return self::getObjectsByRelations(self::getRelationsByObject($object), $object, $exclude_private);
    } // getLinkedObjectsByObject
    
    /**
    * Return objects by array of object - object relations
    *
    * @param array $relations
    * @param boolean $exclude_private Exclude private objects
    * @return array
    */
    static function getObjectsByRelations($relations, $originalObject, $exclude_private = false) {
      if(!is_array($relations)) return null;
      
      $objects = array();
      foreach($relations as $relation) {
        $object = $relation->getOtherObject($originalObject);
        if (!can_access(logged_user(), $object, ACCESS_LEVEL_READ)) continue;
        if($object instanceof ProjectDataObject) {
          if(!($exclude_private && $object->isPrivate())) $objects[] = $object;
        } else {
        	$objects[] = $object;
        }
      } // if
      return count($objects) ? $objects : null;
    } //getObjectsByRelations
    
    /**
    * Remove all relations by object
    *
    * @param ProjectDataObject $object
    * @return boolean
    */
    static function clearRelationsByObject(ApplicationDataObject $object) {
      return self::delete(array('(`object_id` = ? and `object_manager` = ?) or (`rel_object_id` = ? and `rel_object_manager` = ?)', 
      $object->getId(), get_class($object->manager()), $object->getId(),  get_class($object->manager())));
    } // clearRelationsByObject
    
  } // clearRelationsByObject

?>