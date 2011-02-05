<?php
namespace pew;
/**
 * this class is just a bag to hold all output data used by the Renderer in the Theme
 *
 * @package pew
 * @author Dennis Sänger
 */
class Output {
   
	private $bag = array();

	public function __construct() {
		
	}
	
	public function set($key, $value) {
		
		$this->bag[$key] = $value;
	}

	public function getAll() {
		return $this->bag;
	}

}

?>