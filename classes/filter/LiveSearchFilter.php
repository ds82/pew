<?php
namespace pew\filter;

use pew\Item;

class LiveSearchFilter extends PaginationFilter {

	private $search = "";

	public function __construct() {
		// smelly :/
        $this->prepare(null);
	}

	public function prepare($search = null) {

		$search == null && $search = trim(strtolower($_REQUEST['search']));
		strlen($search) > 0 && $this->search = $search;

		// remove unwanted characters form search string
		$this->search = preg_replace('/[%^$]/', '', $this->search);

	}

	public function check(Item $item) {

		if (preg_match('/\*/', $this->search) > 0) {
			// replace * with .* to make a regexp
			$searchExpr = preg_replace('/\*/', '.*', $this->search);
			$searchExpr = '/^'.$searchExpr.'$/';
		} else {
			$searchExpr = '/.*'.$this->search.'.*/';
		}
		
		$toBeSearched = $item->toString();
		$itemMatchesSearch = preg_match($searchExpr, $toBeSearched);

		if ($itemMatchesSearch) {
			// the counter increases if the parent check is called, thus it should only
			// be called if the search string matched
			return parent::check($item);
		} else
			return false;
	}
}

?>