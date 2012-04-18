<?php
namespace pew;
/**
 * This is the pew template engine
 * The class holds all variables that should be available for output,
 * calls the right theme Layout and handles all other output stuff like json display and though on
 *
 * @author Dennis Sänger
 */
use pew\common\ContentType;

class Renderer {

	const CONTROLLER_NAMESPACE = "app";

    private $config;
	private $injector;

	private $output;
	private $theme = null;
    private $template = null;
    private $templateObject = null;
	private $content = "";

	private $class;
	private $method;
	private $format;

	private $showOutput = true;

    private $contentType;

	public function __construct(
        Output $output,
        Config $config,
        Injector $injector,
        Dispatcher $dispatcher,
        ContentType $contentType
    ) {

		$this->config = $config;
		$this->output = $output;
		$this->injector = $injector;
		$this->dispatcher = $dispatcher;
        $this->contentType = $contentType;
	}

	public function prepare($class, $method, $format) {

		$this->class = $class;
		$this->method = $method;
		$this->format = $format;
	}

	public function run() {

		// if output is disabled, abort
		if ($this->showOutput == false) return;

		// if not template was set, set to default template for this call
		if ($this->getTemplate() == null)
			$this->setTemplate($this->class . '/' . 'tpl.' . $this->method . '.php');

		// check theme
		if ($this->theme == null)
			$this->theme = 'themes\\' . $this->config->theme . '\\Layout';

		// make all values from bag locally available ...
		extract($this->output->getAll());
		// ... and the injector
		$__injector = $this->injector;
		// ... and the controller of the layout file
		$__controller = $this->injector->getInstance('pew\Dispatcher')->getController();

        // new template classes
        $matches = array();
        if (preg_match('/class:([a-zA-Z0-9_\\\]*)/', $this->getTemplate(), $matches) == 1)
            $clazzName = $matches[1];
        else $clazzName = self::CONTROLLER_NAMESPACE . '\\' . $this->class . '\\' . ucfirst($this->method) . 'Template';

        if (class_exists($clazzName, true)) {

            $this->templateObject = $this->injector->getInstance($clazzName);
            ob_start();
            $this->templateObject->setOutArr($this->output->getAll());
            $this->templateObject->render();
        	$this->content .= ob_get_clean();

        // check if a legacy template file exists and if it exists catch the content
        } else if (is_file($this->getTemplate())) {

			try {
				ob_start();
                require_once($this->getTemplate());

				$this->content .= ob_get_clean();

			} catch(\Exception $e) {

				$this->content .= ob_get_clean();
				$this->content .= '<div class="rendererError">'
					. 'Catched Exception in renderer with message: '.$e->getMessage()
					. '<br /><br />'
					. nl2br($e->getTraceAsString())
					."</div>";
			}
		} else if ($this->dispatcher->isAjaxCall() OR $this->dispatcher->getRender() == 'json') {

			// if it's an ajax call and a json file is requestet, we can ignore the
			// absence of an template file. the occassion where this behaviour is wanted
			// should be really rare and is handeled by the above if-statement

		} else {

            $this->content = $this->dispatcher->out;
            $this->error('template file not found. expected template: ' . $this->getTemplate() . ' or ' . $clazzName);
        }
      
		// now make the output according to the choosen format
		switch ($this->format) {

			default:
			case 'html':
			    $this->contentType->setContentTypeHeader('html');
                $layout = $this->injector->getInstance($this->theme);
			    $layout->header();
				echo $this->content;
				$layout->footer();
			break;
			case 'tpl':
				// the client wants just the tpl file, but if the request is json
				// return the tpl encoded as json
				if ($this->dispatcher->isAjaxCall()) {

					$data = array(
						'out' => $this->content,
						'debug' => $this->dispatcher->out,
						'status' => $this->dispatcher->getStatus(),
						'data' => $this->output->getAll(), 
					);
                    $this->contentType->setContentTypeHeader('json');
                    echo json_encode($data);

				} else {
                    $this->contentType->setContentTypeHeader('html');
                    echo $this->content;
                }
			break;
			case 'json':
				$ax = array();
				$ax['data'] = $this->output->getAll();
				$ax['out'] = $this->content;
				$ax['debug'] = $this->dispatcher->out;
				$ax['status'] = $this->dispatcher->getStatus();
                $this->contentType->setContentTypeHeader('json');
                echo json_encode($ax);
			break;

		}
	}

	public function setTemplate($tpl) {
		// stupid mistake ... :/
        if (substr($tpl,0,6) != 'class:')
            $this->template = $this->config->controllerNamespace . '/' . $tpl;
        else $this->template = $tpl;
	}
	public function getTemplate() {
		return $this->template;
	}

	public function setTheme($t) {
		if (0 == preg_match('\\', $t)) $t = 'themes\\' . $t . '\\Layout';
		$this->theme = $t;
	}
	public function getTheme() {
		return $this->theme;
	}

	public function getOutput() {
		return $this->output;
	}

	public function addContent($c) {
		$this->content .= $c;
	}

	public function disableOutput() {
		$this->showOutput = false;
	}

   public function error($msg = "") {

		if ($this->config->displayErrors)
			$this->addContent('<p><br />renderer->error(): '.$msg.'<br /></p>');
	}
}

?>