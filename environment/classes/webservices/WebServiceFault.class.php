<?php
	/**
	 * Description of WebServiceFault
	 *
	 * @author awebtech
	 */
	class WebServiceFault extends Exception {
		protected $faultCode = '';
		protected $faultString = '';

		function  __construct($fault_code, $fault_string) {
			parent::__construct($fault_string);
			$this->faultCode = $fault_code;
			$this->faultString = $this->getMessage();
		}

		function getFaultCode() {
			return $this->faultCode;
		}

		function getFaultString() {
			return $this->faultString;
		}

		
	}
?>
