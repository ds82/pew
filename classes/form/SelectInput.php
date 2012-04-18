<?php
namespace pew\form;

class SelectInput extends AbstractFormInput {
   
	private $options = null;

	public function __construct() {
		
	}

   public function prepare($a1, $a2, $opts, $options = null) {

		parent::prepare($a1, $a2, $opts, $options);
		$this->options = $options;
	}
   
	public function setOptions($o) {
		$this->options = $o;
	}

	public function render() {

		// maybe we got a callback function to get the options?
		if ( ! is_array($this->options) AND is_string($this->options) AND method_exists($this->model, $this->options)) {
			
			$this->options = $this->model->{$this->options}();
			
		// just get the options with the default method
		} else if ($this->options == null OR count($this->options) == 0) {

			$method = 'get'.ucfirst($this->field()).'Options';
			
			if ( ! method_exists($this->model, $method)) throw new \Exception(get_class($this->model).' has no method '.$method.'()');
			$this->options = $this->model->{$method}();
		}


		// either way, now we need an options array
		if ( ! is_array($this->options)) throw new Exception('selectInput['.$this->field().', '.$this->value().']: options array is not set!');

		// cache value and name
		$value = $this->value();
		$name = $this->name();
		
  		echo '<li>';
		echo '<label>'.$this->getLabel().'</label>';
		echo '<select size="1" name="'.$name.'">';
		foreach($this->options AS $o) {
			
			$sel = ($value != "" AND $o[0] == $value) ? 'selected="selected"' : '';
			echo '<option value="'.$o[0].'"'.$sel.'>'.$o[1].'</option>';
		}
		echo '</select></li>';
		
		$this->renderErrorMessage();
	}
	
}
?>