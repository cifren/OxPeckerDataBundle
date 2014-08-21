<?php

namespace Earls\OxPeckerDataBundle\Tests\Core;

use Earls\OxPeckerDataBundle\Core\DataProcess;

class DataProcessTest
{
    protected $dataProcess;
    
    protected function setUp()
    {
        $entityManager = $this->getMock('Doctrine\ORM\EntityManager');
        $logger = $this->getMock('Symfony\Bridge\Monolog\Logger');
        
        $this->dataProcess = new DataProcess($entityManager, $logger);
    }

    public function testProcess()
    {
        $config = $this->getMock('Earls\OxPeckerDataBundle\Definition\DataConfigurationInterface');
        $params = array() ;
        
        $this->dataProcess->process($config, $params);
    }

    protected function testCreateDataSources()
    {
        $manager = new DataSourceManager();
        $manager->setEntityManager($this->getEntityManager());
        $manager->setLogger($this->getLogger());

        foreach ($dataSources as $dataSource) {
            $manager->createTableFromDataSource($dataSource);
        }
    }

    protected function testExecuteETLProcesses()
    {
        foreach ($etlProcesses as $etlProcess) {
            $etlProcess->setLogger($this->getLogger());
            if (!$etlProcess->getContext()) {
                $etlProcess->setContext(new Context());
            }

            $etlProcess->process();
        }
    }

    protected function testCreateContext()
    {
        $context = new DataProcessContext($params);

        return $context->setArgs($params);
    }

}
