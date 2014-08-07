<?php

namespace Earls\OxPeckerDataBundle\ETL\Core;

use Knp\ETL\ExtractorInterface;
use Knp\ETL\TransformerInterface;
use Knp\ETL\LoaderInterface;
use Knp\ETL\ContextInterface;

class ETLProcess
{

    protected $context;
    protected $extractor;
    protected $transformers;
    protected $loader;
    protected $logger;

    public function process()
    {
        $context = $this->getContext();
        $extractor = $this->getExtractor();
        $transformers = $this->getTransformers();
        $loader = $this->getLoader();

        $i = 0;
        if (!$extractor) {
            return null;
        }
        while (null !== $input = $extractor->extract($this->getContext())) {
            foreach ($transformers as $transformer) {
                $output = $transformer->transform($input, $context);
                if (!empty($output)) {
                    $input = $output;
                }
            }
            $loader->load($input, $context);
            $i++;
        }
        echo "count : $i\n";

        $loader->flush($context);
    }

    public function getLoader()
    {
        return $this->loader;
    }

    public function getExtractor()
    {
        return $this->extractor;
    }

    public function getTransformers()
    {
        return $this->transformers;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function setLoader(LoaderInterface $loader)
    {
        $this->loader = $loader;
        return $this;
    }

    public function setExtractor(ExtractorInterface $extractor)
    {
        $this->extractor = $extractor;
        return $this;
    }

    public function setTransformers(TransformerInterface $transformers)
    {
        $this->transformers = $transformers;
        return $this;
    }

    public function setContext(ContextInterface $context)
    {
        $this->context = $context;
        return $this;
    }

    public function getLogger()
    {
        if (!$this->logger) {
            throw new \Exception('did you forget to setLogger ?');
        }

        return $this->logger;
    }

    public function setLogger($logger)
    {
        $this->logger = $logger;

        return $this;
    }

}
