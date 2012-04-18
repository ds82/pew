<?php
/**
 * @author dennis
 */
namespace pew\filter;

use PHPUnit_Framework_Testcase;
use pew\filter\PaginationFilter;

class PaginationFilterTest extends PHPUnit_Framework_Testcase {

    private $uut;

    public function setUp() {
        $this->uut = new PaginationFilter();
    }
    public function tearDown() {

    }

    public function testCheck() {
        $this->assertEquals(0, $this->uut->getStart());
        $this->assertEquals(PaginationFilter::DEFAULT_NUM, $this->uut->getNum());
    }


}

?>
