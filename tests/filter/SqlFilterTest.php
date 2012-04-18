<?php
namespace pew\filter;

use PHPUnit_Framework_Testcase;
use pew\filter\SqlFilter;

/**
 * @author dennis
 */
class SqlFilterTest extends PHPUnit_Framework_Testcase {

    private $uut;

    public function setUp() {
        $this->uut = $this->getMockForAbstractClass('pew\filter\SqlFilter');
    }

    public function testIsAnSqlFilter() {
        $this->assertFalse($this->uut->isSqlFilter());
        $this->uut->enableSqlFilter(true);
        $this->assertTrue($this->uut->isSqlFilter());
        $this->uut->enableSqlFilter(false);
        $this->assertFalse($this->uut->isSqlFilter());
    }

}
?>
