<?php
namespace pew;

class Mimetypes extends PewObj {

	private static $data = array(
		'css'	=> array(
			'mime' => 'text/css',
		),
		'js'	=> array(
			'mime' => 'text/javascript',
		),
	);
	
	public function __construct() {
		
	}
	
	public static function getMimetype($type) {
		if (in_array($type, self::$data))
			return self::$data[$type]['mime'];
		else return "";
	}
	
}

?>