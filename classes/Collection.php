<?php
namespace pew;

use pew\filter\BasicFilter;

interface Collection {
	
	public function fetchAll(BasicFilter $filter);
    public function getOverallNumberOfFiles();
}

?>