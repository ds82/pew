<?php
require_once('validator/_validator.php');
class stringValidator extends _validator {

	// TODO implement $notNull
	public static function validate($v, &$res, $notNull, &$msg) {
	
		$v = (string)$v;
		if ($notNull == true AND strlen($v) < 1) {
			$msg = 'stringValidator: string validation failed';
			$res = '';
			return false;
		}
		$res = $v;
		return true;
	}
	
}
?>