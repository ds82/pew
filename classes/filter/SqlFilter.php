<?php
namespace pew\filter;

/**
 * abstract implementation of a filter. write a custom filters for pagination and stuff
 * @author dennis
 *
 */
abstract class SqlFilter extends BasicFilter {

	protected $sqlFilter = false;
	
	public function enableSqlFilter($e) {
		$this->sqlFilter = $e;
	}

    public function isSqlFilter() {
        return $this->sqlFilter;
    }
	
	public abstract function getSqlLimit();
	public abstract function getSqlWhere();
	public abstract function getSqlOrder();

}