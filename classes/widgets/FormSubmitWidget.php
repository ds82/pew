<?php
namespace pew\widgets;

class FormSubmitWidget extends AbstractWidget {

    private $bool = true;

    public function render($model = null) {
		
		$enabled = ($this->bool == true) ? '' : 'disabled="disabled"';
        echo '<input id="'.$this->getCleanName().'" type="submit" value="'.$this->getLabel().'" '.$enabled.' />'."\n";
	}

    // overwrite pre and post render to prevent <li></li> container
    public function preRender() {}
    public function postRender() {}

    public function enableButton($bool) {
        $this->bool = $bool;
    }
}
?>