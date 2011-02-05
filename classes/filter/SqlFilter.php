<?php
require_once('_filter.php');
/**
 * abstract implementation of a filter. write a custom filters for pagination and stuff
 * @author dennis
 *
 */
abstract class _sqlFilter extends _filter {

	protected $sqlFilter = false;
	
	public function enableSqlFilter($e) {
		$this->sqlFilter = $e;
	}
	
	public abstract function getSqlLimit();
	public abstract function getSqlWhere();
	public abstract function getSqlOrder();

}