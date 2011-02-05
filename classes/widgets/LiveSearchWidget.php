<?php
namespace pew\widgets;

class liveSearchWidget extends AbstractWidget {

	public function render() {
		
		$defaultValue = strlen($_REQUEST['search']) > 0 ? $_REQUEST['search'] : ''; 

		$dispatcher = $this->getContext()->requestModule('dispatcher');
		$html = $this->getContext()->requestModule('_html');
		
		echo '<div class="livesearch" id="livesearch'.ucfirst($this->getParent()->getType()).'">';
		echo '<input type="hidden" name="liveSearchUrl" value="'.$dispatcher->getPewUrl(null, null, 'tpl', null).'" />';
		$html->img('search.png', array('class'=>'searchImg'));
		echo '<input class="livesearchInput" name="'.$this->name.'" value="'.$defaultValue.'" />';
		$html->img('purge.png', array('style' => 'visibility:hidden;', 'class'=>'purgeImg'));
		
		echo '</div>';
	}
	
}
?>