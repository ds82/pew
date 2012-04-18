<?php
namespace pew\html;

interface TableLayout {
	
	public function setTableModel($_collection);
	public function getTableHeaders();
	public function getNextTableRow();
	
}
?>