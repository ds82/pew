<?php
namespace pew\filter;

use pew\Item;

class PaginationFilter extends SqlFilter {

	public function check(Item $item) {

		$result = parent::check($item);

		if ($this->isSqlFilter() OR $this->isItemInRange($this->getCounter()))
            return (true && $result);
		else return false;
	}

	public function getSqlLimit() {
		return 'LIMIT '.$this->getStart().','.$this->getNum();
	}
	public function getSqlWhere() {
		
	}
	public function getSqlOrder() {
		
	}
}
?>