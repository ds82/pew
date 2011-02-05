<?php
require_once('_collection.php');
require_once('filter/_sqlFilter.php');

class paginationFilter extends _sqlFilter {

	const DEFAULT_NUM = 10;
	private $start;
	private $num;

	public function __construct($start = null, $num = null) {

		$this->setStart($start);
		$this->setNum($num);
	}

	public function check($item) {

		$result = parent::check($item);
		
		if ($this->sqlFilter == true OR 
			($this->getCounter() > $this->start AND $this->getCounter() <= ($this->start + $this->num))
		) return (true && $result);
		else return false;
	}
	
	public function setStart($s) {
		$this->start = max(0, intval($s));
	}
	public function getStart() {
		return $this->start;	
	}
	public function setNum($n) {
		$this->num = intval($n);
		if ($this->num < 1) $this->num = self::DEFAULT_NUM;
	}
	public function getNum() {
		return $this->num;
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