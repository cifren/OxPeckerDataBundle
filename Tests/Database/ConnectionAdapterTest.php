<?php

namespace Earls\OxPeckerDataBundle\Tests\Command;

use Earls\OxPeckerDataBundle\Database\StandardDBConnectionAdapter;
use Earls\OxPeckerDataBundle\Tests\Command\BaseTestCommand;

class ConnectionAdapterTest extends BaseTestCommand
{
    private $adapter = null;

    protected function setUp()
    {
        $this->adapter = new StandardDBConnectionAdapter($this->getConnection());
    }

    public function testQuery()
    {
        $query = 'select * from pp_stockbook_reports limit 1';
        $result = $this->adapter->query($query);

        $this->assertNotNull($result);
        $this->assertTrue(count($result) == 1);
    }
}
