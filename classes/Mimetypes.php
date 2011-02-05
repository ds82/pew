<?php
namespace pew;

class Mimetypes {

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