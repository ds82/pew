<?php
namespace pew\html;
use pew\widgets;

class ToolbarItem extends widgets\AbstractWidget {
	
	// pseudo constant
	public static $CSS_MODIFIER = array(
		'single', 'multiple', 'always'
	);
	public $DEFAULT_CSS = array(
		'tbItem'
	);
	protected $name = "";
	protected $title = "";
	protected $css = array();
	protected $href = array();
	protected $visibility = "";
    protected $noParameter = false;
	
	public function __construct() {
		// parent::__construct($name, null, null, null, $options);
		
	}

	public function setOptions($name, $visibility, $options = array()) {
		
		$this->name = $name;
		$this->visibility = $visibility;
		$this->href = isset($options['href']) ? $options['href'] : '#'.$name;
		
		$this->css = isset($options['css']) ? $options['css'] : array();
		!is_array($this->css) && $this->css = array($this->css);
		
		$this->title = isset($options['title']) ? $options['title'] : $name;

        if ($options['noParameter'] == true) $this->noParameter = true;
	}

	public function setVisibility($v) {
		$this->visibility = $v;
	}
	public function setHref($h) {
		$this->href = $h;
	}

	protected function getCssString() {
		
		return implode(' ', array_unique(
			array_merge($this->DEFAULT_CSS, array($this->name, $this->visibility), $this->css)
		));
	}

    private function boolToString($bool) {

    }

	public function render($model = null) {
		
		// each item needs exactly one class of {$this->cssModifier}
		if ( $this->visibility == "" OR !in_array($this->visibility, self::$CSS_MODIFIER))
			throw new InvalidArgumentException('A toolbarItem needs at least one of the following css classes: '.implode(', ', $this->cssModifier));
		
		/**
		 * build href
	     * either href is just a string, so just print it
		 * but if href is an array, we assume this format: array('action', 'controller', 'location')
		 * e.g.: ('edit','person','remote') OR ('list','jobs','local')
		 */
		if (is_array($this->href) AND count($this->href) > 0) {
			// using the foreach construct allows a dynamic length of the array
			$href = "#";
			foreach($this->href AS $i => $h) $href .= $h.'.';
			$href = substr($href, 0, -1);
   		} else $href = $this->href;
		
   		echo '<li><a href="'.$href.'" data-no-parameter="'.$this->bool2String($this->noParameter).'" class="'.$this->getCssString().'" title="'.$this->title.'"></a></li>';
	}
	
	public function save() {}
	public function load($args) {}	
}
?>