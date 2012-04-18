<?php
namespace pew\widgets;

class Button extends AbstractWidget {

    protected $onClick = null;

	public function render($model = null) {
		
		echo '<button id="'.$this->getCleanName().'" '.$this->renderOnClick().'>'.$this->getLabel().'</button>'."\n";
	}

    // overwrite pre and post render to prevent <li></li> container
    public function preRender() {}
    public function postRender() {}

    public function setOnClick($js) {
        $this->onClick = $js;
    }

    private function renderOnClick() {
        if ($this->onClick != null)
            return 'onClick="'.$this->onClick.'"';
        else return '';
    }

}
?>