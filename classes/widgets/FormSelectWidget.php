<?php
namespace pew\widgets;

class FormSelectWidget extends AbstractWidget {

	public function render($model = null) {
		
		list($selected, $ids, $values) = $this->get();
		
		($this->getId() != '') ? $id = 'id="'.$this->getId().'"' : $id = '';
		
		echo '<label>'.$this->getLabel().'</label>'."\n";
		echo '<select '.$id.'class="'.$this->getCleanName().' '.$this->getClassAsString().'" size="1" name="'.$this->getName().'">';
		for($i=0; $i<count($ids); ++$i) {
			($ids[$i] == $selected)  ? $s = ' selected="selected"' : $s = "";
			echo '<option value="'.$ids[$i].'"'.$s.'>'.$values[$i].'</option>';
		}
		echo '</select>';
        $this->renderAppendedWidgets();
	}
	
}
?>