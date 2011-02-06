<?php

	/**
	 * Description of WebServiceObject
	 *
	 * @author master
	 */
	abstract class WebServiceObject {
	    protected static $operations = array();
	    protected static $requireAuth = true;

	    abstract static function Init();

	    static function GetOperations() {
		    return self::$operations;
	    }

	    static function IsRequireAuth() {
		    return self::$requireAuth;
	    }
	}
?>
