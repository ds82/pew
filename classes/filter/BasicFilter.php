<?php
namespace pew\filter;

use pew\Item;
use pew\exceptions\NotFoundException;

/**
 * abstract implementation of a filter. write a custom filters for pagination and stuff
 * @author dennis
 *
 */
class BasicFilter {

    const DEFAULT_NUM = 10;

    private $start = 0;
    private $num;

	/**
	 * counts how often this filter was called. this is useful for pagination
	 * @var int
	 */
	private $counter = 0;
    private $overallNumberOfItems = 0;

    /**
     * This map saves the position of an item in the list
     */
    private $positionMap = array();

    public function __construct() {
        $this->num = self::DEFAULT_NUM;
    }

	/**
	 * checks if the items passes this filter
	 * @param Object $item
	 * @return boolean return true if $item passes the filter, false otherwise
	 */
	public function check(Item $item) {
		
        if ($item !== null)
            $positionMap[($item->getId())] = $this->counter;
		++$this->counter;
		return true;	
	}

	public function getCounter() {
		return $this->counter;
	}
	
	public function ignoreLastItem() {
		--$this->counter;
	}

    public function setOverallNumberOfItems($num) {

        $this->overallNumberOfItems = intval($num);
    }

    public function getOverallNumberOfItems() {
        return $this->overallNumberOfItems;
    }

    public function updateOverallNumberOfItems($overallNumberOfItems) {
        if ($this->overallNumberOfItems !== $overallNumberOfItems) {
            $this->setOverallNumberOfItems($overallNumberOfItems);
            $this->checkAndAdjustRange();
        }
    }

    public function hasParameterChanged($start, $number, $overallNumberOfItems) {
        if ($this->start !== $start OR
            $this->num !== $number OR
            $this->overallNumberOfItems !== $overallNumberOfItems
        ) return true;
        else return false;
    }

    public function setRange($start, $number) {

        $this->start = max(0, $start);
        $this->num = ($number > 0) ? $number : self::DEFAULT_NUM;
    }

    public function checkAndAdjustRange() {
        if ($this->getStart() > $this->getOverallNumberOfItems())
            $this->setRange(
                $this->pageToStart($this->getNumberOfPages(), $this->getNum()),
                $this->getNum()
            );
    }

    public function pageToStart($page, $numberOfItemsPerPage) {

        return ($page-1)*$numberOfItemsPerPage;
    }

    public function getNumberOfPages() {
        return ceil($this->getOverallNumberOfItems() / $this->getNum());
    }

    public function getStart() {
        return $this->start;
    }
    public function getNum() {
        return $this->num;
    }

    public function getPageForItem($number) {
        // 0 or 1 items are always page 1
        if ($number < 2) return 1;
        return intval(ceil(($number+0.01)/$this->getNum()));
    }

    public function getPageForItemId($id) {
        
        if (!array_key_exists($id, $this->positionMap))
            throw new NotFoundException("ItemId was not found in positionMap");
        
        $number = $this->positionMap[$id];
        return $this->getPageForItem($number);
    }


    /**
     * Get page according to current start and num values
     */
    public function getCurrentPage() {
        return $this->getPageForItem($this->getStart());
    }

    public function isItemInRange($itemNumber) {
        return $this->isAboveLowerItemBoundary($itemNumber) AND $this->isBelowUpperItemBoundary($itemNumber);
    }

    public function isAboveLowerItemBoundary($itemNumber) {
        return ($itemNumber > $this->getStart());
    }

    public function isBelowUpperItemBoundary($itemNumber) {
        return ($itemNumber <= ($this->getStart() + $this->getNum()));
    }

}
?>