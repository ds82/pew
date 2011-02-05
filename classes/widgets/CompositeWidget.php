<?php
require_once('pew/lib/widgets/_widget.php');

class compositeWidget extends _widget {
	
	private $widgets;
	
	public function render() {
		
		foreach($this->widgets AS $w) {
			$w->render();
		}
	}
	
	
}


?>