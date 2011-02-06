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

	public function __construct(Config $config, ezSQL $db) {

		$this->config = $config;
		$this->db = $db;
	}

	public function checkUser() {
		
		$checksum = $_SESSION['pewAccessChecksum'];
		
	}


   public function prepare() {
	
	
	}
	

	public function run() {
		
		
	}
	


	
}


?>