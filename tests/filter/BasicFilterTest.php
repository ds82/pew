<?php
namespace pew\filter;

use \PHPUnit_Framework_Testcase;
use pew\filter\BasicFilter;

class BasicFilterTest extends PHPUnit_Framework_Testcase {

    private $uut;

    public function setUp() {
        $this->uut = new BasicFilter();
    }

    public function testCheckCounterAndIgnoreLastItem() {

        $this->assertEquals(0, $this->uut->getCounter());
        $this->uut->check(null);
        $this->assertEquals(1, $this->uut->getCounter());
        $this->uut->check(null);
        $this->assertEquals(2, $this->uut->getCounter());
        $this->uut->ignoreLastItem();
        $this->assertEquals(1, $this->uut->getCounter());
    }

    public function testGetOverallNumberOfItems() {
        $this->assertEquals(0, $this->uut->getOverallNumberOfItems());
        $this->uut->setOverallNumberOfItems(10);
        $this->assertEquals(10, $this->uut->getOverallNumberOfItems());
    }


    public function setRangeProvider() {
        return array(
            array(0,10,0,10),

        );
    }

    /**
     * @dataProvider setRangeProvider
     */
    public function testSetRange($start, $num, $expectedStart, $expectedNum) {
        $this->uut->setRange($start, $num);
        $this->assertEquals($expectedStart, $this->uut->getStart());
        $this->assertEquals($expectedNum, $this->uut->getNum());
    }

    public function validRangeProvider() {
        return array(
            array(0,10,5),
            array(10,41,50),
            array(100,1,101),
            array(0,10,10),
        );
    }
    /**
     * @dataProvider validRangeProvider
     */
    public function testItemIsInRange($start, $num, $test) {

        $this->uut->setRange($start, $num);
        $this->assertTrue($this->uut->isItemInRange($test));
    }

    /**
     * @dataProvider validRangeProvider
     */
    public function testIsAboveLowerItemBoundaryTrue($start, $num, $item) {
        $this->uut->setRange($start, $num);
        $this->assertEquals(true, $this->uut->isAboveLowerItemBoundary($item));
    }

    /**
     * @dataProvider validRangeProvider
     */
    public function testIsBelowUpperItemBoundaryTrue($start, $num, $item) {
        $this->uut->setRange($start, $num);
        $this->assertEquals(true, $this->uut->isBelowUpperItemBoundary($item));
    }


    public function invalidRangeProvider() {
        return array(
            array(0,1,0),
            array(100,1,100),
            array(5,10,4),
            array(10,1,12),
        );
    }
    /**
     * @dataProvider invalidRangeProvider
     */
    public function testItemNotInRange($start, $num, $test) {

        $this->uut->setRange($start, $num);
        $this->assertFalse($this->uut->isItemInRange($test));
    }

    /**
     * @dataProvider invalidRangeProvider
     */
    public function testIsAboveLowerItemBoundaryFalse($start, $num, $item) {
        $this->uut->setRange($start, $num);
        $this->assertEquals(($item > $start), $this->uut->isAboveLowerItemBoundary($item));
    }

    /**
     * @dataProvider invalidRangeProvider
     */
    public function testIsBelowUpperItemBoundaryFalse($start, $num, $item) {
        $this->uut->setRange($start, $num);
        $this->assertEquals(($item <= ($start+$num)), $this->uut->isBelowUpperItemBoundary($item));
    }



    public function checkAndAdjustProvider() {
        return array(
            array(0,10,10,0,0)
        );
    }

    /**
     * @dataProvider checkAndAdjustProvider
     */
    public function testCheckAndAdjust($start, $num, $overall, $pageToShow, $adjust) {
    }

    public function pageForItemProvider() {
        return array(
            array(10,0,1),
            array(10,10,2),
            array(10,9,1),
        );
    }

    /**
     * @dataProvider pageForItemProvider
     */
    public function testGetPageForItem($itemsPerPage, $number, $pageShouldBe) {

        $this->uut->setRange(0, $itemsPerPage);
        $this->assertEquals($pageShouldBe, $this->uut->getPageForItem($number));
    }

    public function pageToStartProvider() {
        return array(
            array(1,10,0),
            array(2,10,10),
            array(10,5,45),
        );
    }

    /**
     * @dataProvider pageToStartProvider
     */
    public function testPageToStart($page, $itemsPerPage, $expectedStart) {
        $this->assertEquals($expectedStart, $this->uut->pageToStart($page, $itemsPerPage));
    }

    public function setNumberOfPagesProvider() {
        return array(
            array(10,100,10),
            array(10,10,1),
            array(10,45,5),
        );
    }

    /**
     * @dataProvider setNumberOfPagesProvider
     */
    public function testSetNumberOfPages($num, $overallNumberOfItems, $expectedNumberOfPages) {
        $this->uut->setRange(0, $num);
        $this->uut->setOverallNumberOfItems($overallNumberOfItems);
        $this->assertEquals($expectedNumberOfPages, $this->uut->getNumberOfPages());
    }
}
?>