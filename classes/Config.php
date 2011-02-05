<?php
namespace pew;
/**
 * This is a common Config Class with some default values for pew
 *
 * @package pew
 * @author Dennis Sänger
 */
class Config {
	
	public function __construct() {}
	
	// dispatching
	public $controllerPath = 'app';
	public $urlFormat = '(?P<module>\w+)\.(?P<action>\w+)\.(?P<extension>\w+)';
	public $validExtensions = array('html', 'tpl', 'json');
	
	
}


?>