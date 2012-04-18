<?php
namespace pew\form;

abstract class AbstractFormInput {

	protected $form;
	protected $model;
	private $field;
	private $elementName;
	private $validator;
	
	protected $data;
	protected $label = "";
	
	protected $autoTranslate = true;

	function __construct() {
		
	}

	function prepare($field, $validator = null, $opts = array()) {
   
		$this->field = $field;
		$this->validator = $validator;
		
		$this->opts = $opts;
		
		if (isset($opts['name'])) {
			
			$n =  $opts['name'];
			if (is_array($n)) {
				$this->elementName = 'FORM['.$n[0].']['.$n[1].']';
			} else {
				$this->elementName = 'FORM['.$n[0].']';
			}
		} else $this->elementName = '';
	}

	public function setModel($f, $m) {
		
		//if (! ($f instanceof form OR $f == null)) throw new Exception(get_class($this).': no valid form object given');
		$this->form = $f;
		$this->model = $m;
	}
	public function getModel() {
		return $this->model;
	}
	
	public function setValidator($v) {
		
		$this->validator = $v;
	}
	public function getValidator() {
		
		return $this->validator;
	}
	public function validate() {
		
		if ($this->validator == null) return false;
		else return $this->validator->validate($this->value());
	}
	public function value() {
		
		if ($this->opts['callback']) {

			if (method_exists($this->model, $this->opts['callback'])) {
				$res = $this->model->{$this->opts['callback']}($this->opts['cbParameter']);
				//echo $this->opts['callback'].':'.$this->opts['cbParameter'].":".$res."<br />";
				return $res;
			}
				

		} else {
			$method = 'get'.ucfirst($this->field);

			if ( ! method_exists($this->model, $method)) throw new \Exception(get_class($this->model).' has no method '.$method.'. Can`t fetch form-value');
			return $this->model->{$method}();
	  }
	}
	public function field() {
		return $this->field;
	}
   public function setField($f) {
		$this->field = $f;
	}
   
	public function name() {
		
		if ($this->elementName != '') return $this->elementName;
		return 'FORM['.$this->field().']';
	}
	
	public function set($value) {
		
		if ($this->opts['setter']) {

      	if (method_exists($this->model, $this->opts['setter']))
				return $this->model->{$this->opts['setter']}($value, $this->opts['setterParameter']);

		} else {
			$method = 'set'.ucfirst($this->field);
			if ( ! method_exists($this->model, $method)) throw new Exception(get_class($this->model).' has no method '.$method.'. Can`t set form-value');
			else return $this->model->{$method}($value);
	  }
	}

	public function enableAutoTranslate($b) {
		
		$this->autoTranslate = $b;
	}
	public function setLabel($l) {
		$this->label = $l;
	}
	public function getLabel() {
		
		if ($this->autoTranslate == true)
			return _t('formLabel_'.$this->field());
		else if ($this->label != '')
			return $this->label;
		else throw new Exception(get_class($this).': autoTranslate is OFF and no label is set');
	}

   public function getFormError() {

		// Registry::getInstance()->log(get_class($this).'->getFormError() '.$this->model->getFormError($this->field()));
		return $this->model->getFormError($this->field());
	}
	public function renderErrorMessage() {
		
		$msg = $this->getFormError();
		if (!is_array($msg) OR count($msg) == 0) return;
		
		Registry::getInstance()->log(get_class($this).'->renderErrorMessage() called (field: '.$this->field().') '.json_encode($msg).' count: '.count($msg));
		
		
		if (count($msg) > 0) {
			echo '<li class="error"><ul>';
			foreach($msg AS $m) echo '<li>'.$m.'</li>';
			echo '</ul></li>';
		}		
	}

	public function setOption($field, $value) {
		
		$this->data[$field] = $value;
	}

	public function setLabelClass($css) {
		
		$this->data['label_class'] = $css;
	}
	public function setInputClass($css) {
		
		$this->data['input_class'] = $css;
	}

	public function labelClass() {
	
		if (!is_array($this->data['label_class'])) return;
		if (count($this->data['label_class']) > 0) { 
			echo 'class="'.implode(' ', $this->data['label_class']).'"';
		} else echo '';
	}
	public function inputClass() {
	
		if (!is_array($this->data['input_class'])) return;
		if (count ($this->data['input_class']) > 0) {
			echo 'class="'.implode(' ', $this->data['input_class']).'"';
		} else echo '';
	}
	
	abstract function render();

	public function save() {}
	public function load($args) {}
	
}
?>