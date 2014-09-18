<?php

namespace Earls\OxPeckerDataBundle\ETL\Iteration\Transformer;

use Knp\ETL\TransformerInterface;
use Knp\ETL\ContextInterface;
use Earls\OxPeckerDataBundle\ETL\Iteration\LoggableInterface;
use Psr\Log\LoggerInterface;

class ObjectAlterationTransformer implements TransformerInterface, LoggableInterface
{

    protected $transformerFunction;
    protected $logger;

    public function __construct(\Closure $transformerFunction)
    {
        $this->transformerFunction = $transformerFunction;
    }

    public function transform($object, ContextInterface $context)
    {
        call_user_func($this->transformerFunction, $object);
        
        return $object;
    }

    /**
     * 
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * 
     * @param LoggerInterface $logger
     * @return \Earls\OxPeckerDataBundle\ETL\Iteration\Transformer\ObjectAlterationTransformer
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        
        return $this;
    }

}
