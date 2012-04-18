<?php
namespace pew\widgets;

class liveSearchWidget extends AbstractWidget {

	public function render($model = null) {
		
		$defaultValue = strlen($_REQUEST['search']) > 0 ? $_REQUEST['search'] : ''; 

		$dispatcher = $this->dispatcher;
		$html = $this->injector->getInstance('pew\html\Html');
		
		echo '<div class="livesearch" id="livesearch'.ucfirst($dispatcher->getClass()).'">';
		echo '<input type="hidden" name="liveSearchUrl" value="'.$dispatcher->generateURL(null, null, 'tpl', null).'" />';
		$html->img('search.png', array('style' => 'float: left;', 'class'=>'searchImg'));
		echo '<input class="livesearchInput ignoreUniform" name="'.$this->name.'" value="'.$defaultValue.'" />';
		$html->img('purge.png', array('style' => 'visibility:hidden; margin:3px 0 0 0;', 'class'=>'purgeImg'));
		
		echo '</div>';
	}
	
}
?>