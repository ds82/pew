<?php
/**
 * undocumented 
 *
 * @author Sebastian Proksch
 */
namespace pew;
use pew\exceptions;

class Injector {

	private $bindings = array();
	private $singletons = array();
	private $instances = array();

	public function __construct() {
		$this->bindInstance(__NAMESPACE__ . '\\' . 'Injector', $this);
	}

	public function bind($interface, $implementation, $asSingleton = false) {
		if(!is_string($interface)) throw new InvalidArgumentException('interface is not a string');
		if(!is_string($implementation)) throw new InvalidArgumentException('implementation is not a string');
		if(!is_bool($asSingleton)) throw new InvalidArgumentException('asSingleton is no boolean');

		$this->bindings[$interface] = $implementation;

		if($asSingleton) {
			$this->singletons[] = $interface;
		}
	}

	public function bindInstance($interface, $instance) {
		if(!is_string($interface)) throw new \InvalidArgumentException('interface is not a string');
		if(!($instance instanceof $interface)) throw new \InvalidArgumentException('instance no instance of interface');

		$this->instances[$interface] = $instance;
	}

	public function getBindingFor($fqClassName) {
		$bindingKeys = array_keys($this->bindings);
		if(in_array($fqClassName, $bindingKeys)) {
			return $this->bindings[$fqClassName];
		} else {
			return $fqClassName;
		}
	}

	public function getInstance($fqClassName) {
		if(!is_string($fqClassName)) throw new InvalidArgumentException('classname is not a string');

		$binding = $this->getBindingFor($fqClassName);

		if($this->isInstantiated($binding)) {
			return $this->instances[$binding];
		} else {
			return $this->instantiateAndRegister($binding);
		}
	}

	private function isInstantiated($binding) {
		return in_array($binding, array_keys($this->instances));
	}

	private function instantiateAndRegister($binding) {


		$reflection = new \ReflectionClass($binding);
		$deps = $this->createDependencies($reflection);

		if (count($deps) > 0)
			$newInstance = $reflection->newInstanceArgs($deps);
		else
			$newInstance = $reflection->newInstanceArgs();

		// catch errors, made earlier on binding wrong types together
		if(!($newInstance instanceof $binding))
			throw new \InvalidArgumentException("type mismatch: newInstance is no instance of binding");

		if(in_array($binding, $this->singletons)) {
			$this->instances[$binding] = $newInstance;
		}
		return $newInstance;
	}

	private function createDependencies(\ReflectionClass $reflection) {
		$deps = array();

		$constructor = $reflection->getConstructor();
		if($constructor != null) {
			foreach($constructor->getParameters() as $param) {
				if (!$param->getClass()) continue;
				$depClass = $param->getClass()->getName();
				$deps[] = $this->getInstance($depClass);
			}
		}

		return $deps;
	}
}
?>