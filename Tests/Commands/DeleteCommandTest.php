<?php

namespace Earls\OxPeckerDataBundle\Tests\Commands;

use Earls\OxPeckerDataBundle\Commands\DeleteCommand;
use Earls\OxPeckerDataBundle\Tests\Commands\BaseTestCommand;
use Pp3\DataTierBundle\Reports\InventoryReport;
use Earls\OxPeckerDataBundle\Database\ConnectionAdapter;
use Earls\OxPeckerDataBundle\Database\DoctrineConnectionAdapter;

class DeleteCommandTest extends BaseTestCommand
{    
    
    private $adapter = null;
    
    private $report = null;
    
    
    protected function setUp() {
        $this->adapter = new DoctrineConnectionAdapter($this->createDoctrineConnection('test'));
        $this->report = new InventoryReport($this->getLogger());      
              
    }
    
    public function testExecute() {
        $params = array();
        $rowCountBeforeDelete = $this->getRowCount();
        
        $cmd = new DeleteCommand($this->adapter, $this->report, $this->getLogger());  
        
        $params = array("reportDate = '2014-01-01'");
        $result = $cmd->execute($params);
        $rowCountAfterDelete = $this->getRowCount(); 
        //this works when we have pre-inserted data only
        //$this->assertGreaterThan($rowCountAfterDelete, $rowCountBeforeDelete);
        $this->assertTrue($result);  
    }
    
    private function getRowCount() {
      
        $result = $this->adapter->query('select count(weekId) as numRows from pp_stockbook_reports');
       
        foreach($result as $row) {
            return $row['numRows'];
        }        
    }
}
