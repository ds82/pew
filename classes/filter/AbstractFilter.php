<?php
require_once('_pewObj.php');
/**
 * abstract implementation of a filter. write a custom filters for pagination and stuff
 * @author dennis
 *
 */
abstract class _filter extends _pewObj {

	/**
	 * counts how often this filter was called. this is useful for pagination
	 * @var int
	 */
	private $counter = 0;
	
	/**
	 * checks if the items passes this filter
	 * @param Object $item
	 * @return boolean return true if $item passes the filter, false otherwise
	 */
	public function check($item) {
		
		++$this->counter;
		return true;	
	}

	public function getCounter() {
		return $this->counter;
	}
	
	
}