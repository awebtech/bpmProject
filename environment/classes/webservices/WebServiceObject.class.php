<?php

	/**
	 * Description of WebServiceObject
	 *
	 * @author master
	 */
	abstract class WebServiceObject {
	    protected static $operations = array();

	    abstract static function DefineOperations();

	    static function GetOperations() {
		    return self::$operations;
	    }
	}
?>
