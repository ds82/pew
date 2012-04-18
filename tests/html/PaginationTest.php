<?php
namespace pew\html;

use \PHPUnit_Framework_Testcase;

class PaginationTest extends PHPUnit_Framework_Testcase {

    private $uut;
    private $inputValuesMock;
    private $filterMock;

    public function setUp() {
        $this->inputValuesMock = $this->getMock('pew\InputValues');
        $this->filterMock = $this->getMock('pew\filter\BasicFilter');
        $this->uut = new Pagination(null, $this->inputValuesMock);
        $this->uut->setFilter($this->filterMock);
    }


    public function determineStartWithSetPageProvider() {
        return array(
            array(1,10,0),

        );
    }

    private $currentGetIntegerValues;
    public function mockForGetInteger($what) {
        switch($what) {
            case 'page': return $this->currentGetIntegerValues[0]; break;
            case 'pnum': return $this->currentGetIntegerValues[1]; break;
        }
    }

    /**
     * @dataProvider determineStartWithSetPageProvider
     */
    public function testDetermineRange($page, $itemsPerPage, $expectedStart) {

        $this->currentGetIntegerValues = array($page, $itemsPerPage, $expectedStart);

        // set behaviour of inputValuesMock
        $this->inputValuesMock
            ->expects($this->exactly(2))
            ->method('getInteger')
            ->will($this->returnCallback(array($this, 'mockForGetInteger')));

        // set behaviour of filterMock
        $this->filterMock
            ->expects($this->any())
            ->method('pageToStart')
            ->with($this->equalTo($page))
            ->will($this->returnValue($expectedStart));

        $range = $this->uut->determineRange();
        $this->assertEquals($expectedStart, $range->start);
    }
}
?>