<?php
namespace pew\widgets;


class CompositeWidget extends AbstractWidget {

    // useful for childs that have no li tag on their own
    private $wrapChildsInLiTag = false;

	public function render($model = null) {
		
		// TODO this should be a fieldset
		echo '<ul class="formGroup '.$this->getClassAsString().'" id="'.$this->getCleanName().'FormGroup">'."\n";

        if ($this->wrapChildsInLiTag) echo '<li>';
        foreach($this->getChildren() AS $w) {
			$w->setModel($model);
			
			$w->preRender();
			$w->render($model);
			$w->postRender();
		}
		if ($this->wrapChildsInLiTag) echo '</li>';
        echo '</ul>';
	}

    public function enableInnerLiTag($bool) {
        $this->wrapChildsInLiTag = $bool;
    }

	public function setBoxName($n) {
		
		foreach($this->getChildren() AS $w) {
			$w->setBoxName($n);
		}		
	}
	
	public function preRenderWindget() {
		
		echo '<li>';
	}
	
	public function postRenderWidget() {
		echo '</li>';
	}	
	
	
}


?>