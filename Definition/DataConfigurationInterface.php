<?php

namespace Earls\OxPeckerDataBundle\Definition;

interface DataConfigurationInterface
{

    public function setETLProcesses(array $args);

    public function setParamsMapping();
}
