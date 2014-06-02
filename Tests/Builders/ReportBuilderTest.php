<?php

namespace Earls\OxPeckerDataBundle\Tests\Builders;

use Earls\OxPeckerDataBundle\Builders\ReportBuilder;
use Earls\OxPeckerDataBundle\Database\ConnectionAdapter;
use Pp3\DataTierBundle\Reports\InventoryReport;
use Earls\OxPeckerDataBundle\Command\ListAllCommand;

class ReportBuilderTest extends \PHPUnit_Framework_TestCase
{
    private $builder = null;

    private $report = null;

    private $adapter = null;

    protected function setUp()
    {
        $this->builder = new ReportBuilder();
    }

    private function getAdapter()
    {
        $cmd = $this->builder;
        $executeFunction = $this->getMethod('getConnectionAdapter');

        return $executeFunction->invokeArgs($cmd, array());
    }

    private function getReport()
    {
        return new InventoryReport();
    }

    public function testGetConnectionAdapter()
    {
        $adapter = $this->getAdapter();

        $this->assertNotNull($adapter);
        $this->assertTrue($adapter instanceof ConnectionAdapter);

        $this->adapter = $adapter;
    }

    public function testGetReport()
    {
        $cmd = $this->builder;
        $executeFunction = $this->getMethod('getReport');

        try {
            $executeFunction->invokeArgs($cmd, array('invalidreportype'));
            $this->fail("test should fail on invalid report type\r\n");
        } catch (\Exception $e) {

        }
        $report =  $executeFunction->invokeArgs($cmd, array('InventoryReport'));

        $this->assertNotNull($report);
        $this->assertTrue($report instanceof InventoryReport);
    }

    /**
     * @depends testGetConnectionAdapter
     */
    public function testGetCommand()
    {
        $cmd = $this->builder;
        $executeFunction = $this->getMethod('getCommand');
        $result = $executeFunction->invokeArgs($cmd, array('list', $this->getAdapter(), $this->getReport()));

        $this->assertNotNull($result);
        $this->assertTrue($result instanceof ListAllCommand);

    }

    /**
     * getMethod - used for exposing protected/private methods within a class for testing outside the class
     *              *** this method could be used in many test class files ***
     *
     * @param string name - the name of the method to expose
     *
     * @return method - sounds weird, but it returns a function to the user to call
     *
     * usage:
     *
     * $foo = $this->getMethod('nameOfMethod');
     * $obj = new ClassToPassIntoTheMethod();
     * $var = 'some variable to pass into the method';
     *
     * $foo->invokeArgs($obj, $var);
     *
     */
    protected function getMethod($name)
    {
        $method = $this->getReflectionClass()->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }

    protected function getReflectionClass()
    {
        return new \ReflectionClass('Earls\OxPeckerDataBundle\Builders\ReportBuilder');
    }

}
