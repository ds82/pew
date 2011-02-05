<?php
require_once('filter/paginationFilter.php');

class liveSearchFilter extends paginationFilter {

	private $search = "";
	private $check = true;
	
	public function __construct($search = null, $start = null, $num = null) {

		$search == null && $search = trim(strtolower($_REQUEST['search']));
		strlen($search) > 0 && $this->search = $search;

		parent::__construct($start, $num);
	}

	public function check($item) {

		// check the search first, then call the parent check
		// the counter increases if the parent check is called, thus it should only
		// be called if the search string matched
		if ($this->check && 0 == preg_match('/.*'.$this->search.'.*/', $item->toString())) return false;
		
		$result = parent::check($item);
		
		return $result;
	}
}

?>