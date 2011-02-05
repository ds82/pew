<?php
namespace pew\html;
use pew;

abstract class AbstractHtml extends pew\AbstractEntity {

	protected function _parseClass($v) {

		$r = '';
		if (!is_array($v)) $v = array($v);

		$r .= ' class="';
		foreach ($v AS $e)
			$r .= $e.' ';

		$r = substr($r, 0, -1);
		$r .= '"';

		return $r;
	}

	protected function _parseId($v) {

		$r = '';
		if (!is_array($v)) $v = array($v);
		$r .= ' id="';
		foreach ($v AS $e)
			$r .= $e.' ';
		$r = substr($r, 0, -1);
		$r .= '"';

		return $r;
	}

	public function _parseOptions($opt) {

		if (!is_array($opt)) return;

		$r = '';
		if (array_key_exists('class', $opt)) $r .= $this->_parseClass($opt['class']);
		if (array_key_exists('id', $opt)) $r .= $this->_parseId($opt['id']);
		// TODO seb: is a _parseAlt method needed?
		if (array_key_exists('alt', $opt)) $r .= ' alt="'.$opt['alt'].'"';
		if (array_key_exists('style', $opt)) $r .= ' style="'.$opt['style'].'"';
		if (array_key_exists('title', $opt)) $r .= ' title="'.$opt['title'].'"';

		return $r;

   }
   
	protected function checkPath($path) {
	
		if ('/' != substr($path, -1)) $path .= '/';
		return $path;
	}

	public function load($filter) {}
	public function save() {}

}
?>