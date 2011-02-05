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
	public $controllerNamespace = 'app';
	public $urlFormat = '(?P<class>\w+)\.(?P<method>\w+)\.(?P<render>\w+)';
	public $validExtensions = array('html', 'tpl', 'json');
	public $defaultExtension = 'html';
	
	
}


?>