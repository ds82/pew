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

    public function add($key, $value, $unique = TRUE) {
        if (!is_array($this->bag[$key]))
            $this->bag[$key] = array();
        $this->bag[$key][] = $value;
        if ($unique === TRUE) $this->bag[$key] = array_unique($this->bag[$key]);
    }

	public function getAll() {
		return $this->bag;
	}

}

?>