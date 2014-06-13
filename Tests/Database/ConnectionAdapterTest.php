<?php

namespace Earls\OxPeckerDataBundle\Tests\Database;

use Earls\OxPeckerDataBundle\Database\StandardDBConnectionAdapter;
use Earls\OxPeckerDataBundle\Database\DoctrineConnectionAdapter;
use Earls\OxPeckerDataBundle\Database\DBConnection;
use Earls\OxPeckerDataBundle\Tests\Commands\BaseTestCommand;
 

class ConnectionAdapterTest extends BaseTestCommand
{
    private $adapter = null;
    
    protected function setUp() {    
        
    }
    
     
    public function testDoctrineDBConnection() {
        $adapter = new DoctrineConnectionAdapter($this->createDoctrineConnection());
        $query = 'select * from pp_stockbook_reports limit 1';
        $result = $adapter->query($query);
        
        $this->assertNotNull($result);
        $this->assertTrue(is_array($result));
        $this->assertTrue(count($result) == 1);
    } 
    
}
