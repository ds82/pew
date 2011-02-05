<?php
namespace pew\html;
use pew;

class Toolbar extends pew\AbstractCollection {
	
	private $controller;
	
	public function __construct($controller) {
		
		$this->controller = $controller;
		$this->type = $controller;
	}
	public function __deconstruct() {
		
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
	public function render() {
		
		echo '<ul id="tb'.ucfirst($this->controller).'" class="toolbar" title="'.$this->controller.'">';
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