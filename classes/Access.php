<?php
namespace pew;
/**
 * A generic class to handle access control, user management and rights
 *
 * @package pew
 * @author Dennis Sänger
 */
class Access {

	private $config;
	private $db;
	private $renderer;

	public function __construct(Config $config, ezSQL $db, Renderer $renderer) {

		$this->config = $config;
		$this->db = $db;
		$this->renderer = $renderer; 
		
		// autorun checks?
		if ($config->accessAutorun) {
			$this->checkUser();
		}
	}

	public function checkUser() {
		
		if (intval($_SESSION['pewAccessUid']) < 1 OR strlen($_SESSION['pewAccessPw']) < 1 OR strlen($_SESSION['pewAccessChecksum']) < 1) $this->noAccess();
		// check credentials
		else {
			
			
		}
	}


   public function prepare() {
	
	
	}
	

	public function run() {
		
		
	}
	
   public function noAccess()  {

		// if the access fails, call the renderer with a login/noaccess page
		$this->renderer->prepare('login', 'noaccess', 'html');
		$this->renderer->run();
		exit;
	}

	
}


?>