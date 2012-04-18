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

   private $userTable;

	public function __construct(Config $config, ezSQL $db, Renderer $renderer) {

		$this->config = $config;
		$this->db = $db;
		$this->renderer = $renderer; 

		$this->userTable = $this->config->ezPrefix . $this->config->accessUserTable;
		
		// autorun checks?
		if ($config->accessAutorun) {
			$this->login();
			$this->checkUser();
		}
	}
   

	public function login() {
		
		$email = $this->db->escape($_POST['pewAccessEmail']);
		$pw = $this->db->escape($_POST['pewAccessPassword']);
		
		if (strlen($email) > 0 AND strlen($pw) > 0) {
			$row = $this->db->get_row('SELECT uid, email, pw FROM ' . $this->userTable . ' WHERE email = "'.$email.'" AND pw = PASSWORD("'.$pw.'") LIMIT 1');
			if ($row->uid > 0) {
				// login was successful
				$_SESSION['pewAccessUid'] = $row->uid;
				$_SESSION['pewAccessPw'] = sha1($pw);
				$_SESSION['pewAccessChecksum'] = 'xxx';
			}
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
      
		// return;
		// if the access fails, call the renderer with a login/noaccess page
		$this->renderer->prepare('login', 'noaccess', 'html');
		$this->renderer->run();
		exit;
	}

	
}


?>