<?php
namespace pew\widgets;

class FormHiddenWidget extends AbstractWidget {

	public function render($model = null) {
		echo '<li style="display:none;"><input type="hidden" name="'.$this->getName().'" value="'.$this->get().'" /></li>'."\n";
	}
	
	// prevent <li></li>
	public function preRender() {}
	public function postRender() {}
		
	public function enableReadOnly($bool) {
		$this->readOnly = $bool;
	}
	
}
?>