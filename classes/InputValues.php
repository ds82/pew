<?php
namespace pew;
/**
 * Class for working with form input values
 *
 * @package default
 * @author Dennis Sänger
 * TODO replace with a cleaner implementation in RequestData
 */
class InputValues {
	
	private $post;
	private $get;
	private $request;
	
	public function __construct() {
		
		$this->post = $_POST;
		$this->get = $_GET;
		$this->request = $_REQUEST;
	}

	public function get($i) {
		if (!isset($this->request[$i])) return null;
		return $this->request[$i];
	}
	
	public function getSingleValue($i) {
		
		$val = $this->request[$i];
		if (is_array($val)) return $val[0];
		else return $val;
	}

    public function getString($i) {
        $val = $this->get($i);
        if ($val == null) return null;

        return strval(trim($val));
    }

    public function getJsonString($key) {
        return json_decode(urldecode($this->getString($key)));
    }

    public function getBoolean($i) {
        $val = $this->getString($i);
        if ($val == null) return null;

        if ('true' == $val) return true;
        else return false;
    }

    public function getInteger($i) {
        return intval($this->get($i));
    }

	public function getMultipleValues($i) {
		
		$vals = $this->request[$i];
		if (is_array($vals)) return $vals;
		else {
			$vals = trim($vals);
			if ($vals != '') return array($vals);
			else return array();
		}
	}
	
	public function getValues($i) {
		// TODO sanity checks of input values
		return $this->request[$i];
	}
	
}

?>