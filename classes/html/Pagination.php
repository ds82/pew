<?php
/**
 * this class transforms _collections to paginateable lists
 * @author dennis
 *
 */
require_once('_pewObj.php');
require_once('filter/paginationFilter.php');

class pagination extends _pewObj {

	const SHOW_LOWER_PAGES = 3;
	const SHOW_HIGHER_PAGES = 3;
	
	/**
	 * @var _collection
	 */
	private $collection = null;
	private $entriesPerPage = 10;
	private $start = 0;
	private $pages = 0;
	private $currentPage = 0;
	/**
	 * @var paginationFilter
	 */
	private $filter;

	function __construct(_collection $collection, paginationFilter $filter) {

		intval($_REQUEST['pnum']) < 5 ?
			$this->entriesPerPage = paginationFilter::DEFAULT_NUM :
			$this->entriesPerPage = intval($_REQUEST['pnum']);
		
		// if page Parameter is set, ps is ignored
		$page = intval($_REQUEST['page']);
		if ($page > 0) {

			$this->start = $this->pageToPs($page);
		} else {
			// intval of 'ps' parameter but > 0
			$this->start = max(0, intval($_REQUEST['ps']));
		}
		
		$this->collection = $collection;
		
		$this->filter = $filter;
		$this->filter->setNum($this->entriesPerPage);
		$this->filter->setStart($this->start);
		
		$this->collection->registerFilter($this->filter);
	}

	public function setStart($s) {

		$this->start = intval($s);
		$this->filter->setStart($this->start);
	}
	public function getStart() {
		
		return $this->start;
	}
	public function setNum($ePP) {

		$this->entriesPerPage = max(1, $ePP);
		$this->filter->setNum($this->entriesPerPage);
	}
	public function getNum() {
		
		return $this->entriesPerPage;
	}

	public function pageToPs($page) {
		
		if ($page[0] != '' AND ($page[0] == '-' OR $page[0] == '+')) {
			$page = $this->getPage() + intval($page);
		}
		$page = max(1, intval($page));
		return ($page-1)*$this->getNum();
	}
	public function psToPage($ps) {
		
		return 1 + floor($ps / $this->getNum());
	}
	
	public function numPages() {
		
		$this->pages = ceil($this->collection->getOverallNum() / $this->entriesPerPage);
		return $this->pages;
	}
	
	public function getFirstPage() {
		return 1;
	}
	public function getLastPage() {
		return $this->numPages();
	}
	public function getPage() {
		
		$this->currentPage = ceil(($this->start + 0.1) / $this->entriesPerPage);
		return $this->currentPage;
	}
	
	/**
	 * get url for a specific start & num value. you can overwrite this method im implement your own url handling scheme
	 * @param int $s start with item
	 * @param int $n number of items to show
	 */
	public function getUrl($s = 0, $n = 0) {
		
		$s = intval($s); $n = intval($n);
		return $this->getContext()->requestModule('dispatcher')->getPewURL(null, null, null, array('search' => $_REQUEST['search'], 'ps' => $s, 'pnum' => $n));	
	}

	protected function getLowPage() {
		
		return max(1, ($this->getPage() - self::SHOW_LOWER_PAGES));
	}
	protected function getHighPage() {
		
		return min($this->numPages(), ($this->getPage() + self::SHOW_HIGHER_PAGES));	
	}
	
	// if you want to extend from this class, call preRender() beforce calling render();
	protected function preRender() {
	}

	public function render() {

		$this->preRender();

		echo '<ul class="pagination">';
		
		if ($this->getPage() != $this->getFirstPage())  
			echo '<li class="page lower"><a href="'.$this->getUrl($this->pageToPs('-1')).'">&lt;</a></li>';
		
		if ($this->getLowPage() > $this->getFirstPage()) { 
			echo '<li class="page first"><a href="'.$this->getUrl(0).'">1</a></li>';
			echo '<li class="more">...</li>';
		}
		
		for ($i = $this->getLowPage(); $i <= $this->getHighPage(); ++$i) {

			if ($i < $this->getPage()) $class = "lpage";
			else if($i == $this->getPage()) $class = "current";
			else $class = "hpage";

			echo '<li class="page '.$class.'"><a href="'.$this->getUrl($this->pageToPs($i)).'">'.$i.'</a></li>';
		}

		if ($this->getHighPage() < $this->getLastPage()) { 
			echo '<li class="more">...</li>';
			echo '<li class="page last"><a href="'.$this->getUrl($this->pageToPs($this->getLastPage())).'">'.$this->getLastPage().'</a></li>';
		}
		
		if ($this->getPage() != $this->getLastPage())
			echo '<li class="page higher"><a href="'.$this->getUrl($this->pageToPs('+1')).'">&gt;</a></li>';
				
		echo '<li class="gotopage">'._t('Springe zu Seite:');
		echo '<input type="text" name="page" value="" />';
		echo '</li>';
		echo '<li class="page gotopageButton"><a href="">'._t('Go').'</a></li>';
		echo '</ul>';
	}

}

?>