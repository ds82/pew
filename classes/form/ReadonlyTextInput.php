<?php
require_once('form/_formInput.php');
class roTextInput extends _formInput {
   
	public function render() {
      
		$this->enableAutoTranslate(true);

		echo '<li>';
		echo '<label>'.$this->getLabel().'</label>';
		echo '<input name="'.$this->name().'" value="'.$this->value().'" type="text" readonly="readonly" />';
		echo '</li>';
	}
	
}
?>