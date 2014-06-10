<?php

namespace Earls\OxPeckerDataBundle\Tests\Command;

use Earls\OxPeckerDataBundle\Database\DoctrineConnectionAdapter;
use Earls\OxPeckerDataBundle\Database\DBConnection;
use Earls\OxPeckerDataBundle\Tests\Command\BaseTestCommand;
 

class DoctrineConnectionTest extends BaseTestCommand
{
       
    
    public function testQuery() {
        $conn = new DoctrineConnectionAdapter();
        $result = $conn->query('select * from pp_stockbook_reports');
         
        $this->assertNotNull($result);
        $this->assertTrue(is_array($result));
        $this->assertTrue(count($result) > 0);
      
    }
}
    