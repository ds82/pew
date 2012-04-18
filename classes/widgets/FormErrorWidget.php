<?php
namespace pew\widgets;

class FormErrorWidget extends AbstractWidget {

	public function render($model = null) {

		echo '<label class="error">'.$this->getLabel().'</label>'."\n";
	}

}
?>