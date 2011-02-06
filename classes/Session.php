<?php
namespace pew;
/**
 * super simple class to initiate a php session
 *
 * @package default
 * @author Dennis Sänger
 */
class Session {

	public $sid;
	
	public function __construct() {
		
		$res = session_start();
		$this->sid = session_id();
	}
}

?>