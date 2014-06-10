<?php

namespace Earls\OxPeckerDataBundle\Tests\Command;

use Earls\OxPeckerDataBundle\Command\ImportCommand;
use Earls\OxPeckerDataBundle\Tests\Command\BaseTestCommand;
use Pp3\DataTierBundle\Reports\IngredientUsageReport;
use Earls\OxPeckerDataBundle\Database\ConnectionAdapter;
use Earls\OxPeckerDataBundle\Database\StandardDBConnectionAdapter;
use Earls\OxPeckerDataBundle\Database\DoctrineConnectionAdapter;

class ImportCommandTest extends BaseTestCommand
{
    
    private $adapter = null;
    
    private $report = null;
    
    
    protected function setUp() {
        $connstring = '10.100.2.85|earls|point|jhj@nP';
        
        //$this->adapter = new StandardDBConnectionAdapter($this->getConnection($connstring));
        $this->adapter = new DoctrineConnectionAdapter($connstring);
        $this->report = new IngredientUsageReport($this->adapter, $this->getLogger());      
              
    }
    
    public function testExecuteDate() {
        $params = array(
        //params for the sal_detail table
            'date' => '2013-03-02',
          //  'storeId' => '10105',
            
        //params for the ingredient usage table
         //   'store_id' => '10105',
            "usage_date = '2013-03-01'"
        );
        //$params = array("date <= 2012-02-02", "date >= 2012-02-01");
        $cmd = new ImportCommand($this->adapter, $this->report, $this->getLogger());  
        $cmd->execute($params);
    }   
    /*
    public function testExecuteDateRange() {
        $params = array(
        //params for the sal_detail table
            'startDate' => '2013-03-01',
            'endDate' => '2013-04-01',
       //     'storeId' => '10105',
            
        //params for the ingredient usage table
         //   'store_id' => '10105',
            "usage_date >= '2013-03-01'",
            "usage_date <= '2013-04-01'"
        );
        //$params = array("date <= 2012-02-02", "date >= 2012-02-01");
        $cmd = new ImportCommand($this->adapter, $this->report, $this->getLogger());  
        $cmd->execute($params);
    }  
     * 
     */ 
}
