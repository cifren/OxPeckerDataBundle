<?php

namespace Earls\OxPeckerDataBundle\Definition;

/**
 * 
 */
class Context implements ContextInterface
{

    /**
     *
     * @var array 
     */
    protected $dataSources;
    /**
     *
     * @var array 
     */
    protected $args;
    /**
     *
     * @var array 
     */
    protected $etlProcesses;

    /**
     * 
     * @return array
     */
    public function getDataSources()
    {
        return $this->dataSources;
    }

    /**
     * 
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * 
     * @param array $dataSources
     * @return \Earls\OxPeckerDataBundle\Definition\Context
     */
    public function setDataSources(array $dataSources)
    {
        $this->dataSources = $dataSources;
        return $this;
    }

    /**
     * 
     * @param array $args
     * @return \Earls\OxPeckerDataBundle\Definition\Context
     */
    public function setArgs(array $args)
    {
        $this->args = $args;
        return $this;
    }

    /**
     * 
     * @return array
     */
    public function getEtlProcesses()
    {
        return $this->etlProcesses;
    }

    /**
     * 
     * @param array $etlProcesses
     * @return \Earls\OxPeckerDataBundle\Definition\Context
     */
    public function setEtlProcesses(array $etlProcesses)
    {
        $this->etlProcesses = $etlProcesses;
        return $this;
    }

}
