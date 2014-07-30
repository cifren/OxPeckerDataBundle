<?php

namespace Earls\OxPeckerDataBundle\Definition;

interface DataConfigurationInterface
{

    public function defineDataSources(Array $args);

    public function defineReportTransformers();

    public function setReportClassName();

    public function setParamsMapping();
}
