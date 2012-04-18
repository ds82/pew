<?php
namespace pew\html;
use pew;

class Toolbar {

	private $dispatcher;
	
	public function __construct(pew\Dispatcher $dispatcher) {
		
		$this->dispatcher = $dispatcher;
	}

	public function add($item) {
		
		//if (is_a($item, '_widget')) {
			$item->setParent($this);
			$this->bag[] = $item;
		//} else {
		//	throw new \Exception('_widget expected, '.gettype($item).' given');
		//}
	}
	public function addAll($items) {
		
		foreach ($items AS $i) {
			$this->add($i);
		}
	}	
	public function render($model = null) {
		
		echo '<ul id="tb'.ucfirst($this->dispatcher->getClass()).'" class="toolbar" data-controller="'.$this->dispatcher->getClass().'">';
		foreach($this->bag AS $item) {
			$item->render();
		}
		echo '</ul>';
	}
	
	public function save() {}
	public function load() {}
	public function fetchAll($filter = NULL) {}
	
}

?>