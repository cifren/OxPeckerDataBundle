<?php

namespace Earls\OxPeckerDataBundle\Definition;

interface ContextInterface
{

    /**
     * @return array
     */
    public function getDataSources();

    /**
     * @return array
     */
    public function getArgs();

    /**
     * 
     * @param array $dataSources
     */
    public function setDataSources(array $dataSources);

    /**
     * 
     * @param array $args
     */
    public function setArgs(array $args);

    /**
     * @return array
     */
    public function getEtlProcesses();

    /**
     * 
     * @param array $etlProcesses
     */
    public function setEtlProcesses(array $etlProcesses);
}
