<?php

namespace Earls\OxPeckerDataBundle\Tests\Command;

use Earls\OxPeckerDataBundle\Command\ListAllCommand;
use Earls\OxPeckerDataBundle\Tests\Command\BaseTestCommand;
use Pp3\DataTierBundle\Reports\InventoryReport;
use Earls\OxPeckerDataBundle\Database\StandardDBConnectionAdapter;


class ListAllCommandTest extends BaseTestCommand
{
    private $cmd = null;
    
    protected function setUp() {
        $adapter = new StandardDBConnectionAdapter($this->getConnection());
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
