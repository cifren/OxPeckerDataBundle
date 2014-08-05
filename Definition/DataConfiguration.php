<?php

namespace Earls\OxPeckerDataBundle\Definition;

class DataConfiguration implements DataConfigurationInterface
{

    /**
     * authorized arguments
     *
     * @return null
     */
    public function setParamsMapping()
    {
        return null;
    }

    public function setETLProcesses(array $args)
    {
        return array();
    }

}
