<?php

namespace Earls\OxPeckerDataBundle\Tests\Commands;

use Earls\OxPeckerDataBundle\Commands\ListAllCommand;
use Earls\OxPeckerDataBundle\Tests\Commands\BaseTestCommand;
use Pp3\DataTierBundle\Reports\InventoryReport;
use Earls\OxPeckerDataBundle\Database\DoctrineConnectionAdapter;


class ListAllCommandTest extends BaseTestCommand
{
    private $cmd = null;
    
    protected function setUp() {
        $adapter = new DoctrineConnectionAdapter($this->createDoctrineConnection());
        $report = new InventoryReport($this->getLogger());
        
        $this->cmd = new ListAllCommand($adapter, $report, $this->getLogger());        
    }
    
    public function testExecute() {
        $params = array();
        $result = $this->cmd->execute($params);
        
        $this->assertNotNull($result);
        $this->assertTrue(is_array($result));
        $this->assertTrue(count($result) > 0);        
    }
    
}
