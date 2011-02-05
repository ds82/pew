<?php
require_once('validator/_validator.php');
class stringOrArrayOfStringsValidator extends _validator {

	// TODO implement $notNull
	public static function validate($v, &$res, $notNull, &$msg) {
	
		// is $v an array?
		if (is_array($v)) {
			$tmp = array();
			
			foreach ($v AS $key => $val) {
				if (strlen($val) > 0)
					$tmp[$key] = (string)$val;
			}
			
			if (count($tmp) > 0) {
				$res = $tmp;
				return true;
			} else {
				$msg = 'stringOrArrayOfString validation failed';
				return false;
			}
      // or a string?   
		} else if (strlen($v) > 0){
			
			$res = array((string)$v);
			return true;
		}
		// or neither?
		$msg = 'stringOrArrayOfString validation failed';
		return false;
	}
	
}
?>