<?php

namespace Earls\OxPeckerDataBundle\Core;

class ETLProcess
{

    protected $loader;
    protected $extractor;
    protected $transformers;
    protected $context;

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

    public function setLoader($loader)
    {
        $this->loader = $loader;
        return $this;
    }

    public function setExtractor($extractor)
    {
        $this->extractor = $extractor;
        return $this;
    }

    public function setTransformers($transformers)
    {
        $this->transformers = $transformers;
        return $this;
    }

    public function setContext($context)
    {
        $this->context = $context;
        return $this;
    }

    public function process()
    {
        $context = $this->getContext();
        $loader = $this->getLoader();
        $extractor = $this->getExtractor();
        $transformers = $this->getTransformers();
        
        $i = 0;
        while (null !== $input = $extractor->extract($this->getContext())) {
            try {
                foreach ($transformers as $transformer) {
                    $input = $transformer->transform($input, $context);
                }
                
                echo "load\n";
                $loader->load($input, $context);
            } catch (\LogicException $e) {
                
            }
            $i++;
        }
        echo "$i\n";

        $loader->flush($context);
    }

}
