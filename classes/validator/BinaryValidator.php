<?php
require_once('validator/_validator.php');
class binaryValidator extends _validator {
   
	private static $allowedValues = array(0, 1);
	
	public function setAllowedValues($v1, $v2) {
		
		$this->allowedValues = array($v1, $v2);
	}
	
	// TODO implement $notNull
	public static function validate($v, &$res, $notNull, &$msg) {
	
		if (in_array($v, self::$allowedValues)) {
			$res = (string)$v;
			return true;
		}
	   else {
			$res = null;
			return false;
	   }
	}
	
}
?>