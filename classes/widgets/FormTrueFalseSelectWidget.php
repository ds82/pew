<?php
namespace pew\widgets;

class FormTrueFalseSelectWidget extends AbstractWidget {

	public function render($model = null) {
		
		$selected = $this->get();
		$ids = array('true', 'false');
		$values = array('True', 'False');
		
		echo '<label>'.$this->getLabel().'</label>'."\n";
		echo '<select size="1" name="'.$this->getName().'">';
		for($i=0; $i<count($ids); ++$i) {
			($ids[$i] == strtolower($selected))  ? $s = ' selected="selected"' : $s = "";
			echo '<option value="'.$ids[$i].'"'.$s.'>'.$values[$i].'</option>';
		}
		echo '</select>';
	}
	
}
?>