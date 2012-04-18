<?php
namespace pew\validator;

abstract class AbstractValidator {

	protected $value;
	
	
	/**
	* This function should be overwritten
	*/
	public abstract static function validate($v, &$res, $notNull, &$msg);
	
	public function getValue() {
		
		return $this->value;
	}
	public function setValue($v) {
		
		$this->value = $v;
	}
	
	
}
?>