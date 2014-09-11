<?php

namespace Earls\OxPeckerDataBundle\Core;

use Earls\OxPeckerDataBundle\Definition\DataConfigurationInterface;
use Knp\ETL\Context\Context;
use Doctrine\ORM\EntityManager;
use Earls\OxPeckerDataBundle\ETL\SQL\DataSource\DataSourceManager;
use Earls\OxPeckerDataBundle\Definition\Context as DataProcessContext;
use Symfony\Bridge\Monolog\Logger;
use Earls\OxPeckerDataBundle\ETL\Core\SqlETLProcess;

/**
 * Earls\OxPeckerDataBundle\Core\DataProcess
 */
class DataProcess
{

    /**
     *
     * @var EntityManager 
     */
    protected $entityManager;

    /**
     *
     * @var Logger 
     */
    protected $logger;

    /**
     *
     * @var DataSourceManager 
     */
    protected $dataSourceManager;

    public function __construct(EntityManager $entityManager, Logger $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->dataSourceManager = new DataSourceManager($entityManager, $logger);
    }

    /**
     * Process the data based on the configuration
     * 
     * @param \Earls\OxPeckerDataBundle\Definition\DataConfigurationInterface $config
     * @param array $params
     */
    public function process(DataConfigurationInterface $config, array $params)
    {
        $dataProcessContext = $this->createContext($params);

        $this->getLogger()->notice("PreProcess");
        $config->preProcess($dataProcessContext);
        
        if ($config->getEntityManager()) {
            $this->setEntityManager($config->getEntityManager());
        }

        $etlProcesses = $config->getETLProcesses($dataProcessContext);
        $dataProcessContext->setEtlProcesses($etlProcesses);

        $this->getLogger()->notice("Execute ETL Processes");
        $this->executeETLProcesses($etlProcesses);

        $this->getLogger()->notice("PostProcess");
        $config->postProcess($dataProcessContext);
    }

    /**
     * Execute ETL processes
     * 
     * @param array $etlProcesses
     */
    protected function executeETLProcesses(array $etlProcesses)
    {
        foreach ($etlProcesses as $etlProcess) {
            $etlProcess->setLogger($this->getLogger());
            if (!$etlProcess->getContext()) {
                $etlProcess->setContext(new Context());
            }

            if ($etlProcess instanceof SqlETLProcess) {
                $etlProcess->setDatasourceManager($this->getDatasourceManager());
            }
            $etlProcess->process();
        }
        $this->getDatasourceManager()->clear();
    }

    /**
     * setEntityManager
     * 
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @return \Earls\OxPeckerDataBundle\Core\DataProcess
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    /**
     * getEntityManager
     * 
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * createContext
     * 
     * @param array $params
     * @return DataProcessContext
     */
    protected function createContext(array $params)
    {
        $context = new DataProcessContext($params);

        return $context->setArgs($params);
    }

    /**
     * getLogger
     * 
     * @return \Symfony\Bridge\Monolog\Logger
     * @throws \Exception
     */
    public function getLogger()
    {
        if (!$this->logger) {
            throw new \Exception('did you forget to setLogger ?');
        }

        return $this->logger;
    }

    /**
     * setLogger
     * 
     * @param Logger $logger
     * @return \Earls\OxPeckerDataBundle\Core\DataProcess
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * 
     * @param \Earls\OxPeckerDataBundle\ETL\SQL\DataSource\DataSourceManager $datasourceManager
     * @return \Earls\OxPeckerDataBundle\Core\DataProcess
     */
    public function setDatasourceManager(DataSourceManager $datasourceManager)
    {
        $this->dataSourceManager = $datasourceManager;

        return $this;
    }

    /**
     * 
     * @return DataSourceManager 
     * @throws \Exception
     */
    public function getDatasourceManager()
    {
        if (!$this->dataSourceManager) {
            throw new \Exception('did you forget to setDatasourceManager ?');
        }
        $this->dataSourceManager->setEntityManager($this->getEntityManager());

        return $this->dataSourceManager;
    }

}
