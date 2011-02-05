<?php
require_once('pew/lib/widgets/_widget.php');
class formInputWidget extends _widget {

	public function render() {
		
		echo '<label></label><input name="'.$this->name.'" value="'.$this->getCb.'" />'."\n";
	}
	
}
?>