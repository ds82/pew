<?php
namespace pew;

use \Exception;
use \stdClass;
use logger\Logger;

/**
 * This class maps the current URL to a class and a method
 *
 * @package pew
 * @author Dennis Sänger
 */
class Dispatcher {

	const STATUS_OK = 202;
	const STATUS_NOTFOUND = 404;
	const EXCEPTION_ERROR_CODE = 255;

	private $injector;
	private $context;
	private $config;

	private $protocol;
	private $host;
	private $uri;

	private $requestedUrl = array();
	private $parameter = array();
	private $webrootUrl;

	private $class;
	private $fqClassName;
	private $method;
    private $action;

	// render type (e.g. html, json, etc ...)
	private $render = null;

	// the renderer class
	private $renderer = null;

	private $controller = null;

	// public var for output buffer
	public $out;

	// status code of this request
	// TODO I'm not really satisfied with having a status code in the dispatcher, but I don't know a better place right now
	private $status = 0;

	public function __construct(Injector $injector, Context $context, Config $config) {

		$this->injector = $injector;
		$this->config = $config;
		// use the context to store some values other classes may need
		$this->context = $context;

		list($this->protocol, $_) = preg_split('/\//', $_SERVER['SERVER_PROTOCOL']);
		$this->protocol = strtolower($this->protocol);
		$this->requestedUrl = parse_url($this->protocol.'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI']);
		parse_str($this->requestedUrl['query'], $this->parameter);

		$this->host = $this->requestedUrl['host'];
		$host = (isset($this->requestedUrl['port']) && $this->requestedUrl['port'] != '80') ? 
			$this->requestedUrl['host'].':'.$this->requestedUrl['port'] : $this->requestedUrl['host'];
		$this->uri = $this->requestedUrl['scheme'].'://'.$host.$this->requestedUrl['path'];

		if (substr($this->requestedUrl['path'], -1) === '/') {
			$this->webrootUrl = $this->uri;
		} else $this->webrootUrl = $this->requestedUrl['scheme'].'://'.$host.dirname($this->requestedUrl['path']).'/';
	}

	// try to find the right module and call the method
	public function run() {

		Logger::log('Dispatcher->webrootUrl: ' . $this->webrootUrl);
		$requestedFile = substr($this->uri, strrpos($this->uri, '/')+1);
		//echo $requestedFile; exit;

      // if the requestedFile is empty, lets set the defaultValues and assume a validURL
      if ($requestedFile == "") {

			$m['class'] = $this->config->defaultClass;
			$m['method'] = $this->config->defaultMethod;
			$m['render'] = $this->config->defaultExtension;
	      $validURL = true;

		} else $validURL = preg_match('/'.$this->config->urlFormat.'/', $requestedFile, $m);

		if ($validURL AND $this->check($m)) {

            $this->class = $m['class'];
            $this->action = $this->method = $m['method'];
         	$this->render = $m['render'];


			// pew supports two kinds of controllers
            // one class per action (default since pew 0.2.1, dezember 2011)
            // namespace app\controller\Action.php & app\controller\ActionTemplate.php
            // e.g. jobs\Assign.php & jobs\AssignTemplate.php

            // deprecated (old) way before 0.2.1:
            // app\controller\controller.php & app\controller\tpl.action.php

            $controllerLoaded = false;

            // check new way
            $this->fqClassName = $this->config->controllerNamespace . '\\' . $this->class . '\\' . ucfirst($this->method);

            if (class_exists($this->fqClassName, true)) {
                $controllerLoaded = true;
                $this->method = 'run';
            }
            // old way
            else {
                $this->fqClassName = $this->config->controllerNamespace . '\\' . $this->class . '\\' . $this->class;
                if (class_exists($this->fqClassName, true)) $controllerLoaded = true;
            }

			if ($controllerLoaded == true) {

				$this->controller = $this->injector->getInstance($this->fqClassName);
				if (method_exists($this->controller, $this->method)) {

                    try {

                        ob_start();
    					//header('Status: '.self::STATUS_OK);
    					call_user_func(array($this->controller, $this->method));
    					$this->out .= ob_get_clean();
                        ob_end_clean();

                    } catch (Exception $exception) {

						$exceptionObject = new stdClass();
						$exceptionObject->message = $exception->getMessage();
						$exceptionObject->file = $exception->getFile();
						$exceptionObject->traceAsString = $exception->getTraceAsString();
						
						Logger::log('catched Exception in Dispatcher:');
						Logger::log($exception->getMessage());
						Logger::log($exception->getTraceAsString());
						
						$this->getRenderer()->getOutput()->set('exception', $exceptionObject);
						$this->setStatus(self::EXCEPTION_ERROR_CODE);
						$this->error($exception->getMessage() . '<br /><br />' .$exception->getTraceAsString());
                    }


				} else $this->error('method "'.$this->method.'()" in controller not found');
			} else {
                header("HTTP/1.0 404 Not Found");
                exit;
            }
		}
	}

	/**
	 * generates an URL to a specific method inside a class
	 *
	 * @package default
	 * @author Dennis Sänger
	 * @todo move to separate class (URL Handling in General)
	 */
	public function generateURL($class, $method, $render, $options = array()) {

		if ($class == null) $class = $this->class;
		if ($method == null) $method = $this->method;
		if ($render == null) $render = $this->render;

		$qs = "?";
		if (is_array($options)) foreach($options AS $k => $v) {
			if ($v != '')
                $qs .= $k.'='.urlencode($v).'&';
		}
		if (strlen($qs) <= 1) $qs = "";

        // remove last &
        $qs = substr($qs, 0, -1);

        return $this->webrootUrl . $class . '.' . $method . '.' . $render . $qs;
	}


	/**
	 * call an error page
	 *
	 * @return void
	 * @author Dennis Sänger
	 */
	public function error($m="") {

		// lazy load renderer because we only need it here
		if ($this->config->displayErrors)
			$this->getRenderer()->addContent('<div class="dispatcherError">' . nl2br($m) .'</div>');
	}


	public function isAjaxCall() {
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
	}

	public function getClass() {
		return $this->class;
	}
	public function getFqClassName() {
		return $this->fqClassName;
	}
	public function getMethod() {
		return $this->method;
	}
    public function getAction() {
        return $this->action;
    }
	/**
	 * @return returns the render type (e.g. html, json, etc)
	 */
	public function getRender() {
		return $this->render;
	}
	
	/**
	 * @return returns the renderer class and lazy loads it on demand
	*/
	public function getRenderer() {
		if ($this->renderer === null)
			$this->renderer = $this->injector->getInstance('pew\Renderer');
		return $this->renderer;
	}
	
	public function getWebrootUrl() {
		return $this->webrootUrl;
	}

	public function getController() {
		if ($this->controller != null) return $this->controller;
		else {
			throw new \Exception('No controller loaded!');
		}
	}

	public function setStatus($s) {
		if ($this->status == 0 OR $s < $this->status) $this->status = $s;
	}

	public function getStatus() {
		return $this->status;
	}


	private function check(&$v) {

		// if one of these values is incorrect, lets set the default values
		if (strlen($v['class']) < 1 OR strlen($v['method']) < 1 OR strlen($v['render']) < 1) return false;
		return true;
	}

}

?>