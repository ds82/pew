<?php
namespace pew\widgets;

class TextBoxWidget extends AbstractWidget {

	private $text;
   private $hide = false;

	public function render($model = null) {
		
		if ($this->hide) {
			$display = 'style="display:none;"';
		} else $display = 'style="display:block;"';
		
		echo '<div '.$display.' id="'.$this->getCleanName().'"><p>'.$this->text.'</p></div>'."\n";
	}
	
	public function setText($t) {
		
		$this->text = $t;
	}
	
	public function enableDefaultHide($bool) {
		
		$this->hide = $bool;
	}
	
}
?>