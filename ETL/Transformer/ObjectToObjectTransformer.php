<?php

namespace Earls\OxPeckerDataBundle\ETL\Transformer;

use Knp\ETL\TransformerInterface;
use Knp\ETL\Transformer\DataMap;
use Knp\ETL\ContextInterface;

class ObjectToObjectTransformer implements TransformerInterface
{

    private $className;
    private $mapper;

    public function __construct($className, DataMap $mapper)
    {
        $this->className = $className;
        $this->mapper = $mapper;
    }

    public function transform($data, ContextInterface $context)
    {
        $object = new $this->className;
        

        $this->mapper->set($data, $object);
        echo "{$object->getId()}\n";

        return $object;
    }

}
