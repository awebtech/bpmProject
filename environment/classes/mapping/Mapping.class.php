<?php
	/**
	 * Description of Mapping
	 *
	 * @author awebtech
	 */
	class Mapping {
	    private static $instance = null;

	    function getInstance() {
		    if (!isset(self::$instance)) {
			    self::$instance = new Mapping();
		    }

		    return self::$instance;
	    }

	    function Map($value, $forward = true) {
		    
	    }
	}

?>
