<?php

namespace Earls\OxPeckerDataBundle\ETL\Iteration\Transformer;

use Knp\ETL\TransformerInterface;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AlterationTransformerInterface
 *
 * @author cifren
 */
class AlterationTransformer implements TransformerInterface, LoggableInterface, TransformerInterface
{

    protected $transformerFunction;

    public function __construct($arg1, $arg2)
    {
        //if closure
        if ($arg1 instanceof \Closure) {
            $this->transformerFunction = $arg1;
        } elseif (is_object($arg1)) { //if class and methodName
            $this->transformerFunction = array($arg1, $arg2);
        } else {
            throw new \Exception(sprintf('The first argument can be only a closure or an object, given "%s"', var_export($arg1, true)));
        }
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

    public function transform($data, \Knp\ETL\ContextInterface $context)
    {
        
    }

}
