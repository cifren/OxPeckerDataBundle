<?php

namespace Earls\OxPeckerDataBundle\Definition;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Earls\OxPeckerDataBundle\ETL\SQL\DataSource\ORMDataSource;

class DataConfiguration implements DataConfigurationInterface
{
    protected $entityManager;
    protected $defaultOptions;
    protected $derivedAlias = ORMDataSource::DERIVED_ALIAS;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * authorized arguments.
     */
    public function setParamsMapping()
    {
        return null;
    }

    /**
     * Define all your Etl process here.
     *
     * @param \Earls\OxPeckerDataBundle\Definition\Context $context
     *
     * @return array
     */
    public function getETLProcesses(Context $context)
    {
        return array();
    }

    /**
     * Define all actions you want to execute before loading.
     *
     * @param \Earls\OxPeckerDataBundle\Definition\Context $context
     */
    public function preProcess(Context $context)
    {
    }

    /**
     * Define all actions you want to execute after the process done.
     *
     * @param \Earls\OxPeckerDataBundle\Definition\Context $context
     */
    public function postProcess(Context $context)
    {
    }

    /**
     * Define array of DataSources executed by DataSourceManager.
     *
     * @param \Earls\OxPeckerDataBundle\Definition\Context $context
     *
     * @return array
     */
    public function getDataSources(Context $context)
    {
        return array();
    }

    /**
     * getLogger.
     *
     * @return \Symfony\Bridge\Monolog\Logger
     *
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
     * setLogger.
     *
     * @param \Symfony\Bridge\Monolog\Logger $logger
     *
     * @return \Earls\OxPeckerDataBundle\Definition\DataConfiguration
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    protected function setOptions(array $defaultOptions)
    {
        return $defaultOptions;
    }

    public function getOptions()
    {
        return $this->setOptions($this->getDefaultOptions());
    }

    protected function getDefaultOptions()
    {
        if (!$this->defaultOptions) {
            $this->defaultOptions = array(
                'activate-flamingo' => false,
            );
        }

        return $this->defaultOptions;
    }

    protected function setDefaultOptions(array $defaultOptions)
    {
        $this->defaultOptions = $defaultOptions;

        return $this;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManager $entityManager
     *
     * @return \Earls\OxPeckerDataBundle\Definition\DataConfiguration
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    public function setQueueGroupName($name, array $args)
    {
        return null;
    }

    public function setQueueUniqueId($name, array $args)
    {
        return null;
    }
}
