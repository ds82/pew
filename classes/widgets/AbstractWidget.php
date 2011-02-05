<?php
namespace pew\widgets;
use pew\html;

abstract class AbstractWidget extends html\AbstractHtml {
	
	private $name;
	private $getCb;
	private $setCb;
	private $validator;
	private $parent;

	private $options;
	
	private $children = array();

	public function __construct(
		$name, $getCb, $setCb, $validator, $options = array()
	) {

		$this->name = $name;
		$this->getCb = $getCb;
		$this->setCb = $setCb;
		$this->validator = $validator;
		$this->options = $options;
	}
	
	public function setParent($p) {
		$this->parent = $p;
	}
	public function getParent() {
		return $this->parent;
	}	

	public function addChild(_widget $w) {
		
		$this->children[] = $w;
	}
	public function removeChild(_widget $w) {
		
		$k = array_search($w, $this->children);
		if ($k != false) {
			unset($this->children[$k]);
			return true;
		} 
		return false;
	}
	public function getChildren() {
		return $this->children;
	}



	abstract public function render();
	
	
}

?>