<?php

namespace Earls\OxPeckerDataBundle\Tests\Command;

use Earls\OxPeckerDataBundle\Database\DoctrineConnectionAdapter;
use Earls\OxPeckerDataBundle\Database\DBConnection;
use Earls\OxPeckerDataBundle\Tests\Commands\BaseTestCommand;
use Pp3\DataTierBundle\Configuration\ReportConfiguration;

class DoctrineConnectionTest extends BaseTestCommand
{
       
    
    public function testQuery() {
        $reportConfig = new ReportConfiguration('dev', 'test');
        
        $conn = new DoctrineConnectionAdapter($this->createDoctrineConnection());
        $result = $conn->query('select * from pp_stockbook_reports');
         
        $this->assertNotNull($result);
        $this->assertTrue(is_array($result));
        $this->assertTrue(count($result) > 0);
      
    }
}
    