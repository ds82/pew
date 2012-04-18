<?php 
namespace pew;

abstract class AbstractCollection implements \Iterator {
	
	// bag contains items which implement _entity
	protected $bag = array();
	// needed for pagination
	protected $overallNum = 0;
	// default filter for fetchAll()
	protected $filter = null;
	
	// thus a collection is a container for entities, every collection
	// should have a type, which specifies the kind of entities it contains
	protected $type = null;
	
	public function getType() { 
		
		if ($this->type == null) 
			throw new Exception(get_class($this).': Collections need to have a specified type!');
		return $this->type;
	}
	/**
	 * @deprecated Use getType() instead
	 */
	public function getCollectionType() {
		return $this->getType();
	}
	
	public function getAll() {
		return $this->bag;
	}
	public function saveAll() {
		foreach($bag AS $b)
			$b->save();		
	}
	public function size() {
		return count($bag);
	}
	public function getOverallNum() {
		return $this->overallNum;
	}
	public function setOverallNum($v) {
		$this->overallNum = $v;
	}
	public function registerFilter($f) {
		$this->filter = $f;
	}
	public function set($k, $v) {
		if (is_array($this->bag[$k]))
			$this->bag[$k][] = $v;
		else $this->bag[$k] = $v;
	}
	public function get($k) {
		return $this->bag[$k];
	}
	protected function getBag() {
		return $this->bag;
	}
	protected function setBag($bag) {
		$this->bag = $bag;
	}
	
	public function toArray() {
		$tmp = array();
		while ($t = $this->next()) {
			$tmp[] = $t->toArray();
		}
		return $tmp;
	}
	
	public abstract function fetchAll();
	
	// iterator interface
	public function next() {
		
		if (current($this->bag) != null) {
			$t = current($this->bag);
			next($this->bag);
			return $t;
		} else {
			reset($this->bag);
			return null;
		}
	}
	public function current() {
		return current($this->bag);
	}
	public function key() {
		return key($this->bag);
	}
	public function rewind() {
		reset($this->bag);
	}	
	public function valid() {
		return current($this->bag) == '';
	}
	
}

?>