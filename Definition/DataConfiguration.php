<?php

namespace Earls\OxPeckerDataBundle\Definition;

use Earls\OxPeckerDataBundle\Definition\Context;
use Symfony\Bridge\Monolog\Logger;

class DataConfiguration implements DataConfigurationInterface
{

    /**
     *
     * @var Logger 
     */
    protected $logger;

    /**
     * authorized arguments
     *
     * @return null
     */
    public function setParamsMapping()
    {
        return null;
    }

    /**
     * Define all your Etl process here
     * 
     * @param \Earls\OxPeckerDataBundle\Definition\Context $context
     * @return array
     */
    public function getETLProcesses(Context $context)
    {
        return array();
    }

    /**
     * Define all actions you want to execute before loading
     * 
     * @param \Earls\OxPeckerDataBundle\Definition\Context $context
     */
    public function preProcess(Context $context)
    {
        
    }

    /**
     * Define all actions you want to execute after the process done
     * 
     * @param \Earls\OxPeckerDataBundle\Definition\Context $context
     */
    public function postProcess(Context $context)
    {
        
    }

    /**
     * Define array of DataSources executed by DataSourceManager
     * 
     * @param \Earls\OxPeckerDataBundle\Definition\Context $context
     * @return array
     */
    public function getDataSources(Context $context)
    {
        return array();
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
     * @param \Symfony\Bridge\Monolog\Logger $logger
     * @return \Earls\OxPeckerDataBundle\Definition\DataConfiguration
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;

        return $this;
    }

}
