<?php
namespace pew;
/**
 * This class maps the current URL to a class and a method
 *
 * @package pew
 * @author Dennis Sänger
 */
class Dispatcher {
	
	const STATUS_OK = 202;
	const STATUS_NOTFOUND = 404;

	private $injector;
	private $context;
   private $config;
	private $host;
	private $uri;
   
	private $class;
	private $method;
	private $render;

	public function __construct(Injector $injector, Context $context, Config $config) {

		$this->injector = $injector;
		$this->config = $config;
		// use the context to store some values other classes may need
		$this->context = $context;
		
		$this->host = $_SERVER['HTTP_HOST'];
		$this->uri = $_SERVER['REQUEST_URI'];
	}
   
	// try to find the right module and call the method
	public function run() {
		
		$requestedFile = substr($this->uri, strrpos($this->uri, '/')+1);
		$validURL = preg_match('/'.$this->config->urlFormat.'/', $requestedFile, $m);

		if ($validURL AND $this->check($m)) {
	      
			// set values
			$this->class = $m['class'];
			$this->method = $m['method'];
			$this->render = $m['render'];
	 	
			$clazz = $this->config->controllerNamespace . '\\' . $this->class;
			if (class_exists($clazz, true)) {
				
				$instance = $this->injector->getInstance($clazz);

				if (method_exists($instance, $this->method)) {
				
					Header('Status: '.$this->STATUS_OK);
					$instance->$m['method']();
				
				} else $this->error();
				
			} else $this->error();
			
		}
		
	}
	
	/**
	 * generates an URL to a specific method inside a class
	 *
	 * @package default
	 * @author Dennis Sänger
	 */
	public function generateURL($class, $method, $render) {
		
	}
	
	
	/**
	 * call an error page 
	 *
	 * @return void
	 * @author Dennis Sänger
	 */
	public function error() {
	
		echo '<br />dispatcher->error()';
	}

   
	public function getClass() {
		return $this->class;
	}
	public function getMethod() {
		return $this->method;
	}
	public function getRender() {
		return $this->render;
	}


	private function check($v) {
	
		if (strlen($v['class']) < 1 OR strlen($v['method']) < 1 OR strlen($v['render']) < 1) return false;
		return true;
	}
	
	
}

?>