<?php
require_once('validator/_validator.php');
class intValidator extends _validator {

	// TODO implement $notNull
	public static function validate($v, &$res, $notNull, &$msg) {
	
		$res = (int)$v;
		return true;
	}
	
}
?>