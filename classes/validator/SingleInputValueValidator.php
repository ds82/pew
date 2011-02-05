<?php
require_once('validator/_validator.php');
class singleInputValueValidator extends _validator {
   
	/**
	* this validator is for get/post parameter which could be an array or a single value
	* if its an array: return the first element
	* if its an single value: return the value
	*/
	// TODO implement $notNull
	public static function validate($v, &$res, $notNull, &$msg) {  
		
		if (is_array($v)) $res = (string)$v[0];
		else if (strlen($v) > 0) $res = (string)$v;
		else if ($notNull) return false;
		else $res = '';
		return true;
	}
	
}
?>