<?php
require_once('pew/lib/_formLayout.php');
  
class formV2 extends _pewObj {

	private $model;
	private $layout;
	
	public function __construct($model, $layout) {

		$this->model = $model;
		$this->layout = $layout;

		$this->layout->setModel($model);
		$this->layout->setupWidgets();
	}
	
	
	
	
	public function render() {
	
	}
	
	
	
	
	
}
?>