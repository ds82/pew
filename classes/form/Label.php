<?php
require_once('form/_formInput.php');
class label extends _formInput {

   private $value;

   function __construct($a1, $a2, $opts, $options = null) {
	
		parent::__construct($a1, $a2, $opts);
		$this->value = $opts;
	}
	
	public function render() {

		echo '<li>';
		echo '<label>'._t($this->value).'</label>';
		echo '</li>';
	}
	
}
?>