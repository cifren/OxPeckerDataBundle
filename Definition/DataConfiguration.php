<?php

namespace Earls\OxPeckerDataBundle\Definition;

use Doctrine\Common\Collections\ArrayCollection;

class DataConfiguration implements DataConfigurationInterface
{

    /**
     * 
     * @param Array $args
     * @return \Doctrine\Common\Collections\ArrayCollection of Earls\OxPeckerDataBundle\Model\DataSource
     */
    public function defineDataSources(array $args)
    {
        return new ArrayCollection();
    }

    /**
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection of Knp\ETL\TransformerInterface
     */
    public function defineReportTransformers()
    {
        return new ArrayCollection();
    }

    /**
     * 
     * @return String
     */
    public function setReportClassName()
    {
        return null;
    }

    /**
     * authorized arguments
     *
     * @return null
     */
    public function setParamsMapping()
    {
        return null;
    }

}
