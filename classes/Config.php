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
	
	// common
	public $theme = 'standard';
	public $version = 'alpha1';

	// database example configuration
	/*
	public $ezEngine		= 'mysql';
	public $ezHost 		= 'localhost';
	public $ezUser 		= 'root';
	public $ezPassword 	= 'root';
	public $ezDatabase	= 'database';
   */

	// class autoloading
	public $autoloadClasses = array();
	
	// dispatching
	public $controllerNamespace = 'app';
	public $urlFormat = '(?P<class>\w+)\.(?P<method>\w+)\.(?P<render>\w+)';
	public $validExtensions = array('html', 'tpl', 'json');
	
	// some default values
	public $defaultClass = 'index';
	public $defaultMethod = 'main';
	public $defaultExtension = 'html';

	// error Handling
	public $displayErrors = true;
	
	
}
?>