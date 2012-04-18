<?php
namespace pew\form;

class TextInput extends AbstractFormInput {
   
	public function render() {

		$this->enableAutoTranslate(true);
		echo '<li>';
		echo '<label>'.$this->getLabel().'</label>';
		echo '<input name="'.$this->name().'" value="'.$this->value().'" type="text" />';
		echo '</li>';

		$this->renderErrorMessage();
	}
	
}
?>