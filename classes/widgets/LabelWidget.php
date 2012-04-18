<?php
namespace pew\widgets;

class LabelWidget extends AbstractWidget {

    private $wrapWidgetInLi = true;

	public function render($model = null) {
		
		$css = '';
        if ($this->hasCSS()) {
            $css = ' class="'.$this->getClassAsString().'"';
        }
        echo '<label'.$css.'>'.$this->getLabel().'</label>'."\n";
	}

    public function enableListTags($bool) {
        $this->wrapWidgetInLi = $bool;
    }

    public function preRender() {
        if ($this->wrapWidgetInLi) echo '<li>';
    }
    public function postRender() {
        if ($this->wrapWidgetInLi) echo '</li>';
    }
	
}
?>