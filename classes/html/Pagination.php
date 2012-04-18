<?php
namespace pew\html;
use pew;
use pew\filter;
use pew\InputValues;
use pew\Context;
use pew\Dispatcher;
use pew\Collection;
use pew\filter\PaginationFilter;

use stdClass;

/**
 * this class transforms _collections to paginateable lists
 * @author dennis
 *
 */
class Pagination {

	const SHOW_LOWER_PAGES = 3;
	const SHOW_HIGHER_PAGES = 3;

	private $dispatcher;
	private $inputValues;
	
	/**
	 * @var _collection
	 */
	private $collection = null;
	/**
	 * @var paginationFilter
	 */
	private $filter = null;
   
	public function __construct(
        Dispatcher $dispatcher,
        InputValues $iv
    ) {
		$this->dispatcher = $dispatcher;
		$this->inputValues = $iv;
	}

    public function setCollection(Collection $collection) {
        $this->collection = $collection;
    }
    public function setFilter(PaginationFilter $filter) {
        $this->filter = $filter;
    }

	function prepare() {

        $range = $this->determineRange();
        $this->filter->setRange($range->start, $range->num);
        $this->filter->setOverallNumberOfItems($this->collection->getOverallNumberOfFiles());

        $this->filter->checkAndAdjustRange();
	}

    public function determineRange() {

        $value = new stdClass();

        // determine num
        $numberParameter = $this->inputValues->getInteger('pnum');
        $num = ($numberParameter < 5) ?
            PaginationFilter::DEFAULT_NUM :
            $numberParameter;
        $value->num = $num;

        // determine start
        // if page Parameter is set, ps is ignored
        $page = $this->inputValues->getInteger('page');
        if ($page > 0)
            $start = $this->filter->pageToStart($page, $value->num);
        else
            // intval of 'ps' parameter but > 0
            $start = max(0, $this->inputValues->getInteger('ps'));

        $value->start = $start;
        return $value;
    }

	private function isIncreaseOrDecreasePage($page) {
		return ($page[0] == '-' OR $page[0] == '+');
	}

	public function pageToPs($page) {
		
		if ($this->isIncreaseOrDecreasePage($page))
			$page = $this->filter->getCurrentPage() + intval($page);
		$page = max(1, intval($page));
		
		return $this->filter->pageToStart($page, $this->filter->getNum());
	}
	public function psToPage($ps) {
		
		return 1 + floor($ps / $this->getNum());
	}
	
	public function getNumberOfPages() {
		
		return $this->filter->getNumberOfPages();
	}
	
	public function getFirstPage() {
		return 1;
	}
	public function getLastPage() {
		return $this->filter->getNumberOfPages();
	}

	/**
	 * get url for a specific start & num value. you can overwrite this method 
	 * when implement your own url handling scheme
	 * @param int $s start with item
	 * @param int $n number of items to show
	 */
	public function getUrl($s = 0, $n = 0) {
		
		$s = intval($s); $n = intval($n);
		return $this->dispatcher->generateUrl(null, null, 'html', array('search' => $_REQUEST['search'], 'ps' => $s, 'pnum' => $n));
	}

	protected function getLowPage() {
		
		return max(1, ($this->filter->getCurrentPage() - self::SHOW_LOWER_PAGES));
	}
	protected function getHighPage() {
		
		return min($this->filter->getNumberOfPages(), ($this->filter->getCurrentPage() + self::SHOW_HIGHER_PAGES));
	}
	
	// if you want to extend from this class, call preRender() beforce calling render();
	protected function preRender() {
	}

	public function render() {

		$this->preRender();
		echo '<ul class="pagination">';
		
		if ($this->filter->getCurrentPage() != $this->getFirstPage())
			echo '<li class="page lower"><a href="'.$this->getUrl($this->pageToPs('-1')).'">&lt;</a></li>';
		
		if ($this->getLowPage() > $this->getFirstPage()) { 
			echo '<li class="page first"><a href="'.$this->getUrl(0).'">1</a></li>';
			echo '<li class="more">...</li>';
		}
		
		for ($i = $this->getLowPage(); $i <= $this->getHighPage(); ++$i) {

			if ($i < $this->filter->getCurrentPage()) $class = "lpage";
			else if($i == $this->filter->getCurrentPage()) $class = "current";
			else $class = "hpage";

			echo '<li class="page '.$class.'"><a href="'.$this->getUrl($this->pageToPs($i)).'">'.$i.'</a></li>';
		}

		if ($this->getHighPage() < $this->getLastPage()) { 
			echo '<li class="more">...</li>';
			echo '<li class="page last"><a href="'.$this->getUrl($this->pageToPs($this->getLastPage())).'">'.$this->getLastPage().'</a></li>';
		}
		
		if ($this->filter->getCurrentPage() != $this->getLastPage())
			echo '<li class="page higher"><a href="'.$this->getUrl($this->pageToPs('+1')).'">&gt;</a></li>';
				
		echo '<li class="gotopage"><span class="gotopageText">'._t('Webgui.Pagination.GoToPage').'</span>';
		echo '<input type="text" class="ignoreUniform" name="page" value="" />';
		echo '</li>';
		echo '<li class="page gotopageButton ignoreUniform"><a href="">'._t('Webgui.Pagination.Go').'</a></li>';
		echo '</ul>';
	}
}
?>