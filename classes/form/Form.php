<?php
require_once('_html.php');
class form extends _html {
   
	protected $method;
	protected $action;
	protected $model;
	protected $format;
	
	protected $data = array();

	function __construct($model, $action = null, $method = "POST", $format = null) {
		
		$this->model = $model;
	   if (!is_object($this->model) OR !is_subclass_of($this->model, '_entity')) 
			throw new Exception(get_class($this).': model has to be an object');   
		
		$this->method = $method;
		$this->action = $action;
		$this->format = $format;
	}
	
	public function _setup() {
		
	}
   
	public function begin() {

		if ($this->action == null) {
			$format = ($this->format != null) ? $this->format : 'tpl';
			$this->action = $this->getContext()->requestModule('pew')->getPewURL(null, null, $format);
		}

		// TODO ajax should be enabled elsewhere
		// enable ajax for all form
		$this->data['form']['class'][] = 'ajax';
		
		// TODO find a better id
		$pew = $this->getContext()->requestModule('pew');
		$formId = $pew->getController().ucfirst($pew->getAction());
		
		// form header
		// TODO nicer
		echo '<form id="'.$formId.'" method="'.$this->method.'" action="'.$this->action.'" '.$this->_parseOptions($this->data['form']).'>';
		echo '<input name="submit" value="1" type="hidden" />';
		echo '<input name="id" value="'.$this->model->getId().'" type="hidden" />';
		echo '<ul>';   	
	}
	
	public function end($buttons = true) {
		
		if ($buttons) {
	      echo '<li>';
	      echo '<input type="submit" value="'._t('save').'" '.$this->getSubmitClass().' />';
			echo '<input type="reset" value="'._t('reset').'" />';
			echo '</li>';
	  } 	
	  echo '</ul>';
	  echo '</form>';		
	}
	/**
	 * undocumented function
	 *
	 * @param string $model 
	 * @param string $layout 
	 * @param array $groups specifies the groups to render
	 * @return void
	 * @author Dennis Sänger
	 */
	public function render($model, $layout = null, $groups = null) {
	   
		if ($layout == null) $layout = $model;
	
		if (!$layout instanceof _iFormLayout) throw new Exception('layout must implement _iFormLayout interface');
		$layout->setFormModel($model);

		if ($groups == null)
			$groups = $layout->getFormGroups();

		foreach($groups AS $g) {
			$elements = $layout->getFormElements($g);
			
			if (is_array($elements)) {
				$layout->groupHeader($g);
				foreach($elements AS $e) {
				   if (!is_subclass_of($e, '_formInput')) throw new Exception('form elements must inherit from _formInput ('.json_encode($e).')');
					$e->setModel($this, $model);
					Registry::getInstance()->log(get_class($this).'->render(): try to render '.get_class($e));
					$e->render();
				}
				$layout->groupFooter($g);
			}
		}
	}
	
	public function setFormOption($opt, $value) {
	
		$this->data['form'][$opt] = $value;
	}
	public function getFormOption($opt) {
		
		return $this->data['form'][$opt];
	}
	// TODO change to form options!!
	public function setSubmitClass($class) {

		//$this->layout->render();
		$this->data['submit_class'] = $class;
	}
	public function getSubmitClass() {
		
		if (!is_array($this->data['submit_class'])) return;
		return 'class="'.implode(' ', $this->data['submit_class']).'"';
	}
	public function setFormat($f) {
		$this->format = $f;
	}
	
}
?>