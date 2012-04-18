<?php
namespace pew;
/**
 * context object for centralized property management
 * @author Sebastian Proksch
 *
 * This class should no longer be used to inject modules, use the DI Container instead
 * It is only used to inject properties
 * @author Dennis Sänger
 */
class Context {

	/**
	 * @var context
	 */
	private static $instance = null;

	function __construct() {
		self::$instance = $this;
	}

	/**
	 * associative array of properties (key -> value)
	 */
	private $modules = array();

	/**
	 * associative array of registered module (interface -> instanciated obj)
	 */
	private $properties = array();

	/**
	 * array of all id, that specifies constants
	 */
	private $constants = array();

   /**
    * get an instance of context. context is a singelton, if you want a `clean` context, call $context->cleanAll()
    *
    * @return void
    * @author Dennis Sänger
    */
	public static function getInstance() {

		if (self::$instance == null) {
			self::$instance = new Context();
			//throw new \Exception('a module tried to get a Context-Instance but none existed');
		}
		return self::$instance;
	}
	/**
	 * clean all variables. Use with care.
	 * @author Dennis Sänger
	 */
	public function cleanAll() {

		$this->modules = array();
		$this->properties = array();
		$this->constants = array();
	}

	/**
	 * registers an object as an implementation for the given class/interface name
	 *
	 * @param $name string the class/interface name
	 * @param $obj object instantiated object
	 * @throws TypeException $obj is not an instantiated object
	 * @throws MimatchException $obj is not an "instance of" $name
     * @deprecated
	 */
	public function registerModule($name, $obj) {
		if(!is_object($obj))
			throw new exceptions\TypeException('$obj is no object');

		// disable instanceof check while migrating to namespace
		// if(!$obj instanceof $name) {
		// 	$parents = class_parents($obj);
		//          $interfaces = class_implements($obj);
		// 	throw new exceptions\MismatchException('type mismatch, '.get_class($obj).' is no '.$name.'. extends: '.implode(', ', $parents) . '. interfaces: ' . implode(', ', $interfaces));
		// }
		$this->modules[$name] = $obj;
	}

	/**
	 * request the instantiated object for the given class/interface name
	 *
	 * @param $name string name of the class/interface
	 * @return the requested instance
	 * @throws KeyNotFoundException no module is registered for $name
     * @deprecated
	 */
	public function requestModule($name) {
		if(array_key_exists($name, $this->modules))
			return $this->modules[$name];
		else
			throw new exceptions\KeyNotFoundException('no module "'.$name.'" registered for this context');
	}

	/**
	 * check if a specific module is registered
	 *
	 * @param $name string name of the class/interface
	 * @return bool result
     * @deprecated
	 */
	public function moduleExists($name) {
		return array_key_exists($name, $this->modules);
	}

	/**
	 * set a property in the context, existing ones are overwritten
	 *
	 * @param $key string specifies the property
	 * @param $value mixed the property is set to this value
	 * @throws NotAllowedException the key already exists as a constant
	 */
	public function setProperty($key, $value) {
		if(in_array($key, $this->constants))
			throw new exceptions\NotAllowedException('"'.$key.'" is already defined as a constant');

		$this->properties[$key] = $value;
	}

	/**
	 * set a constant in the context
	 *
	 * @param $key string specifies the property
	 * @param $value mixed the property is set to this value
	 * @throws NotAllowedException the key already exists as a constant
	 */
	public function setFinalProperty($key, $value) {
		if(in_array($key, $this->constants))
			throw new exceptions\NotAllowedException('"'.$key.'" is already defined as a constant');

		$this->constants[] = $key;
		$this->properties[$key] = $value;
	}

	/**
	 * get a property from the context
	 *
	 * @param $key string specifies the property
	 * @return the requested property
	 * @throws KeyNotFoundException the requested property does not exist
	 */
	public function getProperty($key) {
		if(array_key_exists($key, $this->properties))
			return $this->properties[$key];
		else
			throw new exceptions\KeyNotFoundException('no property "'.$key.'" set in this context');
	}

	/**
	 * check if a specific property exists
	 *
	 * @param $key string specifies the property
	 * @return bool result
	 */
	public function propertyExists($key) {
		return array_key_exists($key, $this->properties);
	}

	/**
	 * For debugging purpose
   */
	public function dump() {

		echo '<pre>';
		foreach ($this->properties AS $k => $v) {

			echo $k.' => '.$v.' <br />';
		}
		echo '</pre>';
	}
}
?>