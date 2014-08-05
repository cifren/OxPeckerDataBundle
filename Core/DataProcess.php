<?php

namespace Earls\OxPeckerDataBundle\Core;

use Earls\OxPeckerDataBundle\Definition\DataConfigurationInterface;
use Knp\ETL\Context\Context;
use Doctrine\ORM\EntityManager;
use Earls\OxPeckerDataBundle\DataSource\DataSourceManager;

/**
 * Earls\OxPeckerDataBundle\Core\DataProcess
 */
class DataProcess
{

    protected $entityManager;

    /**
     * 
     * @param \Earls\OxPeckerDataBundle\Definition\DataConfigurationInterface $config
     * @param array $params
     * 
     * @return null
     */
    public function process(DataConfigurationInterface $config, array $params)
    {
        $this->setEntityManager($config->getEntityManager());

        $dataSources = $config->getDataSources($params);
        $this->createDataSources($dataSources);

        $etlProcesses = $config->getETLProcesses($params);

        foreach ($etlProcesses as $etlProcess) {

            if (!$etlProcess->getContext()) {
                $etlProcess->setContext(new Context());
            }

            $etlProcess->process();
        }
    }

    protected function createDataSources($dataSources)
    {
        $manager = new DataSourceManager();
        $manager->setEntityManager($this->getEntityManager());

        foreach ($dataSources as $dataSource) {
            $manager->createTableFromDataSource($dataSource);
        }
    }

    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }

}
