<?php

namespace Earls\OxPeckerDataBundle\Tests\Command;

use Earls\OxPeckerDataBundle\Command\DeleteCommand;
use Earls\OxPeckerDataBundle\Tests\Command\BaseTestCommand;
use Pp3\DataTierBundle\Reports\InventoryReport;
use Earls\OxPeckerDataBundle\Database\StandardDBConnectionAdapter;

class DeleteCommandTest extends BaseTestCommand
{

    private $adapter = null;

    private $report = null;

    protected function setUp()
    {
        $this->adapter = new StandardDBConnectionAdapter($this->getConnection());
        $this->report = new InventoryReport($this->getLogger());

    }

    public function testExecute()
    {
        $params = array();
        $rowCountBeforeDelete = $this->getRowCount();

        $cmd = new DeleteCommand($this->adapter, $this->report, $this->getLogger());

        $params = array("reportDate = '2014-01-01'");
        $result = $cmd->execute($params);
        $rowCountAfterDelete = $this->getRowCount();

        $this->assertGreaterThan($rowCountAfterDelete, $rowCountBeforeDelete);
    }

    private function getRowCount()
    {
        $conn = $this->getConnection()->getConnection();
        $result = $conn->query('select count(weekId) as numRows from pp_stockbook_reports');

        foreach ($result as $row) {
            return $row['numRows'];
        }
    }
}
