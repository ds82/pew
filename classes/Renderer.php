<?php
namespace pew;
/**
 * This is the pew template engine
 * The class holds all variables that should be available for output, 
 * calls the right theme Layout and handles all other output stuff like json display and though on
 *
 * @author Dennis Sänger
 */
class Renderer {

	private $config;
	private $injector;

	private $output;
   private $template = null;

	public function __construct(Output $output, Config $config, Injector $injector) {

		$this->config = $config;
		$this->output = $output;
		$this->injector = $injector;
	}
	
	public function run($__class, $__method, $__format) {

		// if not template was set, set to default template for this call
		if ($this->getTemplate() == null)
			$this->setTemplate($this->config->controllerNamespace . '/' . $__class . '/' . 'tpl.' . $__method . '.php');
      
		// make all values from bag locally available
		extract($this->output->getAll());

		// first off all, check if a template file exists and if it exists catch the content
		if (is_file($this->getTemplate())) {

			try {
				ob_start();
				require_once($this->getTemplate());	
				$__content = ob_get_clean();
			
			} catch(\Exception $e) {
			
				$__content = ob_get_clean();
			}
		} else echo 'template file not found';

		// now make the output according to the choosen format
		switch ($__format) {
			
			default:
			case 'html':
				$clazz = 'themes\\' . $this->config->theme . '\\Layout';
				$layout = $this->injector->getInstance($clazz);
			   $layout->header();
				echo $__content;
				$layout->footer();
			break;
			case 'tpl':
				echo $__content;
			break;
			case 'json':
				
			break;
			
		}		
		
	}
	
	public function setTemplate($tpl) {
		$this->template = $tpl;
	}
	
	public function getTemplate() {
		return $this->template;
	}
	

}
?>