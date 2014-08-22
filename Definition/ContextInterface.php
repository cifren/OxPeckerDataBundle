<?php

namespace Earls\OxPeckerDataBundle\Definition;

interface ContextInterface
{

    /**
     * @return array
     */
    public function getArgs();

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
