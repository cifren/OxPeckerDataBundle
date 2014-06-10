<?php

namespace Earls\OxPeckerDataBundle\Tests\Database;

use Earls\OxPeckerDataBundle\Database\StandardDBConnectionAdapter;
use Earls\OxPeckerDataBundle\Database\DoctrineConnectionAdapter;
use Earls\OxPeckerDataBundle\Database\DBConnection;
use Earls\OxPeckerDataBundle\Tests\Command\BaseTestCommand;
 

class ConnectionAdapterTest extends BaseTestCommand
{
    private $adapter = null;
    
    protected function setUp() {    
        
    }
    
        
    public function testStandardDBConnection() {
        $adapter = new StandardDBConnectionAdapter($this->getConnection());
        $query = 'select * from pp_stockbook_reports limit 1';
        $result = $adapter->query($query);
        
        $this->assertNotNull($result);
        $this->assertTrue(is_array($result));
        $this->assertTrue(count($result) == 1);
    } 
    
    public function testDoctrineDBConnection() {
        $adapter = new DoctrineConnectionAdapter();
        $query = 'select * from pp_stockbook_reports limit 1';
        $result = $adapter->query($query);
        
        $this->assertNotNull($result);
        $this->assertTrue(is_array($result));
        $this->assertTrue(count($result) == 1);
    } 
    
    public function testDoctrineConnectionString() {
        $connString = '10.100.2.85|earls|point|jhj@nP';
        
        $adapter = new DoctrineConnectionAdapter($connString);
        $query = 'select * from pp_stockbook limit 1';
        $result = $adapter->query($query);
        
        $this->assertNotNull($result);
        if(!is_array($result)) {
            echo "$result\r\n";
        }
        $this->assertTrue(is_array($result));
        $this->assertTrue(count($result) == 1);
    }
}
