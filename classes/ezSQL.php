<?php
namespace pew;
/**
 * wrapper class for ezSQL
 *
 * @package pew
 * @author Dennis Sänger
 */
class ezSQL {

	//private $config;
	private $db;
 
	public function __construct(Injector $injector, Config $config) {
      
		// include required ezSQL Files
		require_once('ezSQL/shared/ez_sql_core.php');
		// include db specific file
		switch($config->ezEngine) {
			
			default:
			case 'mysql':
				require_once('ezSQL/mysql/ez_sql_mysql.php');
				$this->db = $injector->getInstance('ezSQL_mysql');
				$this->db->quick_connect($config->ezUser, $config->ezPassword, $config->ezDatabase, $config->ezHost);
			break;
		}
	}

	// wrapper for the ezSQL db object
	// this magic function will redirect all calls on this object to the concrete ezSQL object
	public function __call($name, $args) {
		
		return call_user_func_array(array($this->db, $name), $args);
	}
	
}


?>