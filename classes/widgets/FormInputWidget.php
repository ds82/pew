<?php
namespace pew\widgets;

class FormInputWidget extends AbstractWidget {

    private $readOnly = false;
    private $hide = false;

	public function render($model = null) {
		echo '<label>'.$this->getLabel().'</label><input name="'.$this->getName().'" value="'.$this->get().'" '.($this->readOnly == true ? "readonly" : "").' type="text" />'."\n";
        $this->renderAppendedWidgets();
	}

		
	public function enableReadOnly($bool) {
		$this->readOnly = $bool;
	}

    public function preRender() {
        if ($this->hide) {
      			$display = 'style="display:none;"';
      	} else $display = '';
        echo '<li '.$display.'>';
    }

    public function enableDefaultHide($bool) {
   		$this->hide = $bool;
   	}

}
?>