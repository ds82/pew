<?php
require_once('validator/_validator.php');
class fileNameValidator extends _validator {
   
	// TODO FIXME implement a real filename check
	public static function validate($v, &$res, $notNull, &$msg) {
	
		$v = (string)$v;
		if ($notNull == true AND strlen($v) < 1) {
			$msg = 'fileNameValidator: invalid filename';
			$res = '';
			return false;
		}
		$res = $v;
		return true;
	}
	
}
?>