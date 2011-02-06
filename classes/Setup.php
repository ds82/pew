<?php
namespace pew;
/**
 * this class configures the class behaviour for pew classes.
 * it can be overwritten by the user through config/Setup
 *
 * @package default
 * @author Dennis Sänger
 */
class Setup {
	
	public function __construct(Injector $injector) {
		
		$injector->bind('pew\Output', 'pew\Output', true);
		$injector->bind('pew\Renderer', 'pew\Renderer', true);
		$injector->bind('pew\Context', 'pew\Context', true);
		$injector->bind('pew\ezSQL', 'pew\ezSQL', true);
	}
	
	
}

?>