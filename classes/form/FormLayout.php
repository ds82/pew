<?php
namespace pew\form;

abstract class FormLayout {
	
	private $rootWidget;
	private $model;
	
	public function __construct() {
	}
	
	public function setModel($model) {
		$this->model = $model;
	}
		
	public function create($widget, $name, $getCb, $setCb, $validator, $options) {
		
		$this->pew()->lib('widgets/'.$widget);
		return new $widget($name, $getCb, $setCb, $validator, $options);
	}	
		
	abstract public function setupWidgets();

	public function render() {
		
		
		
		
	}

	
	
	
	
}

?>                                      