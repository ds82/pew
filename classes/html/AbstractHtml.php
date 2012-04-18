<?php
namespace pew\html;
use pew;

abstract class AbstractHtml {

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

        if (array_key_exists('alt', $opt)) $r .= ' alt="'.$opt['alt'].'"';

        if (array_key_exists('style', $opt)) $r .= ' style="'.$opt['style'].'"';
		if (array_key_exists('title', $opt)) $r .= ' title="'.$opt['title'].'"';
		
		if (array_key_exists('width', $opt)) $r .= ' width="'.$opt['width'].'"';
		if (array_key_exists('height', $opt)) $r .= ' height="'.$opt['height'].'"';
		
		return $r;

   }

    protected function bool2String($bool) {
        if ($bool == true) return 'true';
        else return 'false';
    }

	protected function checkPath($path) {
	
		if ('/' != substr($path, -1)) $path .= '/';
		return $path;
	}

	public function load($filter) {}
	public function save() {}

}
?>