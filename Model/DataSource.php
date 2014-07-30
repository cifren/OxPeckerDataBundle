<?php

namespace Earls\OxPeckerDataBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Knp\ETL\ExtractorInterface;

/**
 * Earls\OxPeckerDataBundle\Model\DataSource
 */
class DataSource implements DataSourceInterface
{

    protected $extractor;
    protected $transformers;
    protected $entityName;
    protected $dataMap;

    public function __construct()
    {
        $this->transformers = new ArrayCollection();
    }

    public function getExtractor()
    {
        return $this->extractor;
    }

    public function getTransformers()
    {
        return $this->transformers;
    }

    public function setExtractor(ExtractorInterface $extractor)
    {
        $this->extractor = $extractor;
        return $this;
    }

    public function setTransformers(ArrayCollection $transformers)
    {
        $this->transformers = $transformers;
        return $this;
    }

}
