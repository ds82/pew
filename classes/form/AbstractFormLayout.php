<?php
namespace pew\form;

use pew;

abstract class AbstractFormLayout {
	
	protected $model;
	protected $widgets = array();
	protected $data;
	
	private $action;
	private $boxName = '';
	protected $id = '';
	private $enableAjaxForm = false;

	protected $errors = array();
	
	public function __construct() {
		
	}

	/**
	 * @deprecated
	 */
	public function prepare($model, $data = null) {
		$this->model = $model;

		// check if form was submited
		if ($data != null AND count($data) > 0) {

			$this->model->save($data);
		}
	}
	
	public function setAction($a) {
		$this->action = $a;
	}
	public function setBoxName($n) {
		$this->boxName = $n;
	}
	public function setId($id) {
		$this->id = $id;
	}
	public function enableAjaxForm($bool) {
		$this->enableAjaxForm = $bool;
	}
	
	public function proccessForm($data) {
	
		// if (count($data) > 0) {
		// 	print '<pre>';
		// 	print_r($data);
		// 	print '</pre>';
		// }
		
	}
	
	public function getModel() {
		return $this->model;
	}
	public function setModel($m) {
		$this->model = $m;
	}
	
   public function addWidget($widget) {

		$widget->setModel($this->model);
		$this->widgets[] = $widget;
	}

   public abstract function setupWidgets();

	public function setData($d) {
		$this->data = $d;
	}
   
   public function render() {
		
		$this->setupWidgets();
		
		$this->preRender();
		
		foreach($this->widgets AS $w) {
			
			// boxname
			if ($this->boxName != '')
				$w->setBoxName($this->boxName);
			
			$w->preRender();
			$w->render($this->model);
			$w->postRender();
		}
		
		$this->postRender();
	}
	
	public function preRender() {
	
		$class = array();
		if ($this->enableAjaxForm == true) $class[] = 'ajax';
		
		echo '<form id="'.$this->id.'" action="'.$this->action.'" method="POST" class="'.implode(' ', $class).'">';
		echo '<input type="hidden" name="'.$this->id.'" value="1" />';
		echo '<ul>';
	}
	
	public function postRender() {

		echo '</ul></form>';
	}
	
	public function setErrors($err) {
		$this->errors = $err;
	}
	
	public function getErrors() {
		return $this->errors;
	}
	
}
?>