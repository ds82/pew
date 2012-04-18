<?php
namespace pew\widgets;

class FormTextWidget extends AbstractWidget {

    private $hide = false;

	public function render($model = null) {

        $id = '';
        if ($this->getId() !== null) $id = ' id="'.$this->getId().'"';

        echo '<label>'.$this->getLabel().'</label><label'.$id.' style="clear:none;">'.$this->get().'</label>'."\n";
        $this->renderAppendedWidgets();
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