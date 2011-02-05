<?php
namespace pew\Html;
class Html extends AbstractHtml {

	protected $css = array();
	protected $js = array();

	public static $obj = null;

	function __construct() {

	}
	function __destruct() {

	}

	// @todo implement path handling
	public function addCss($file, $path = null, $version = '1.0') {
		
		if ($path == null) $path = $this->getThemeUrl('css');
		$this->css[] = array(
			'file' => $file,
			'version' => $version,
			'path' => $path
		);
	}

	// @todo implement path handling
	public function addJs($file, $path = null, $name = "", $version = '1.0', $req = array()) {

		if ($path == null) $path = $this->getThemeUrl('js');
		$this->js[] = array(
			'file' => $path.$file,
			'name' => $name,
			'version' => $version,
			'req' => $req
		);
	}
	public function css($file,  $path = null, $version = '1.0') {

		if ($path == null) $path = $this->getThemeUrl('css');
		echo '<link rel="stylesheet" type="text/css" href="'.$this->checkPath($path).$file.'" />
';
		// ?version='.$version.'
	}
	public function js($file,  $path = null, $name = '', $version = '1.0', $req = array()) {

		if ($path == null) $path = $this->getThemeUrl('js');
		echo '<script type="text/javascript" src="'.$this->checkPath($path).$file.'"></script><!-- '.$name.' -->
';
	}
	// @todo fix path handling
	public function getJsOutput() {

		foreach($this->js AS $j)
				echo '<script type="text/javascript" src="'.$j['path'].$j['file'].'"></script><!-- '.$j['name'].' -->
';
	}
	// @todo fix path handling
	public function getCssOutput() {

		foreach($this->css AS $c)
			echo '<link rel="stylesheet" type="text/css" href="'.$c['path'].$c['file'].'?version='.$c['version'].'" />
';
	}

	/**
	 * Write HTML Code for an image
	 * @param string $file
	 * @param array $opts
	 * @param string $path
	 * TODO make it work with external images which are not in the theme path
	 * TODO change location of $path parameter to make it consistent with other functions
	 */
	public function img($file, $opts = array(), $path = NULL) {

		$opt = $this->_parseOptions($opts);
		echo '<img src="'.$this->getContext()->getProperty('THEME_URL').'img/'.$file.'" '.$opt.' />';
	}
	/**
	* TODO
	*/
	public function getUrl($s = '') {
		if (strlen($s) > 0 ) $s .= '/';
		return $this->getContext()->getProperty('WEBROOT_URL').$s;
	}
	public function url($s) {
		echo $this->getUrl($s);
	}
	/**
	 * returns the URL of the current theme with a final slash
	 *
	 * @param string $s 
	 * @return void
	 * @author Dennis Sänger
	 */
	public function getThemeUrl($s = '') {
		//if (strlen($s) > 0 ) $s .= '/';
		return $this->getContext()->getProperty('THEME_URL').$s;
	}
	public function themeUrl($s = '') {
		echo $this->getThemeUrl($s);
	}
	public function getDefaultThemeUrl($s = '') {
		//if (strlen($s) > 0 ) $s .= '/';
		return $this->getUrl().'themes/default/'.$s;
	}
	public function defaultThemeUrl($s = '') {
		echo $this->getDefaultThemeUrl($s);
	}
	public function getPewResource($s) {
		return $this->getContext()->getProperty('WEBROOT_URL').'pew/'.$s;
	}
	public function pewResource($s) {
		echo $this->getPewResource($s);
	}
	
	/**
	* Outputs HTML Code for a table
	* @param $_collection
	* @param $_iTableLayout
	* @param array $opts - array with various options
	*/
	public function table($collection, $layout = null, $opts = array()) {

		if ($layout == null) $layout = $collection;
		
		if (!$layout instanceof TableLayout) throw new \Exception("layout must implement TableLayout interface");
		$layout->setTableModel($collection);

		echo '<table class="data" border="0" title="'.$collection->getCollectionType().'">';
		echo '<tr>';

		$head = $layout->getTableHeaders();

		foreach ($head AS $i => $v) {
			if (is_array($v)) {
				$opts = $this->_parseOptions($v[1]);
				$v = $v[0];
			} else $opts = '';
			print '<th'.$opts.'>'.$v.'</th>';
		}
		echo '</tr>';

		while($row = $layout->getNextTableRow() AND $row != null) {
			echo '<tr>';
			foreach ($row AS $r) {
				if (is_array($r)) {
					$opts = $this->_parseOptions($r[1]);
					$r = $r[0];
				} else $opts = '';
				echo '<td'.$opts.'>'.$r.'</td>';
			}
			echo '</tr>';
		}
		echo '</table>';
	}


}
?>