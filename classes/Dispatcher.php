<?php
namespace pew;

class Dispatcher {
	
	const STATUS_OK = 202;
	const STATUS_NOTFOUND = 404;

   private $config;


	public function __construct(Config $config) {

		$this->config = $config;
	}
	
	
}

?>