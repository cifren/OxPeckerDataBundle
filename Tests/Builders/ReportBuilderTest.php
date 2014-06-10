<?php

namespace Earls\OxPeckerDataBundle\Tests\Builders;

use Earls\OxPeckerDataBundle\Builders\ReportBuilder;
use Earls\OxPeckerDataBundle\Database\ConnectionAdapter;
use Pp3\DataTierBundle\Reports\InventoryReport;
use Earls\OxPeckerDataBundle\Commands\ListAllCommand;

include_once ('app/AppKernel.php');

class ReportBuilderTest extends \PHPUnit_Framework_TestCase
{
    private $builder = null;
    
    private $report = null;
    
    private $adapter = null;
    
    protected function setUp() {
        $this->builder = new ReportBuilder();
    }
    
    public function testHandlerRequest() {
        $cmd = $this->builder;
        
        $connection = $this->createDoctrineConnection();
        $action = 'import';
        $params= array('date' => '2014-03-01');
        $reportType = 'IngredientUsage';
        $cmd->handleRequest($action, $params, $reportType, $connection);
    }
    
    private function getAdapter() {
        $cmd = $this->builder;
        //first we pass it a connection
        $executeFunction = $this->getMethod('setConnection');
        $executeFunction->invokeArgs($cmd, array($this->createDoctrineConnection()));
        
        //now we get the adapter back
        $executeFunction = $this->getMethod('getConnectionAdapter');
        return $executeFunction->invokeArgs($cmd, array());
    }
    
    private function getReport() {
        return new InventoryReport();    
    }
    
    public function testGetConnectionAdapter() {
        
        $adapter = $this->getAdapter();
        
        $this->assertNotNull($adapter);
        $this->assertTrue($adapter instanceof ConnectionAdapter);
        
        $this->adapter = $adapter;
    }
    
    public function testGetReport() {
        $cmd = $this->builder;
        //first we pass it a connection
        $executeFunction = $this->getMethod('setConnection');
        $executeFunction->invokeArgs($cmd, array($this->createDoctrineConnection()));
        
        $executeFunction = $this->getMethod('getReport');
        
        try{
            $executeFunction->invokeArgs($cmd, array('invalidreportype'));
            $this->fail("test should fail on invalid report type\r\n");
        }catch(\Exception $e) {
            
        }
        $report =  $executeFunction->invokeArgs($cmd, array('InventoryReport'));
        
        $this->assertNotNull($report);
        $this->assertTrue($report instanceof InventoryReport);
    }
    
    /**
     * @depends testGetConnectionAdapter
     */
    public function testGetCommand() {
        
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
    
    /**
     * createDoctrineConnection     since doctrine likes to be instantiated from the kernel's container
     *                              we load->boot the kernel, and get the entity manager from the container.
     *                              From there we are actually referencing the EM as the connection object
     *                              but we have hidden that from the user in the adapter interface
     */
    private function createDoctrineConnection() {
        $kernel = new \AppKernel(
            isset($options['config']) ? $options['config'] : 'dev',
            isset($options['debug']) ? (boolean) $options['debug'] : true
            );       
        $kernel->boot(); 
        $dbName = 'earls';
        $connection = $kernel->getContainer()->get('doctrine')->getManager($dbName);    
        $connection->getConfiguration()->setSQLLogger(null);
        
        return $connection;
                    
    }
    
}

