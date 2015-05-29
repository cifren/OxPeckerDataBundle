<?php

namespace Earls\OxPeckerDataBundle\ETL\Iteration\Transformer;

use Knp\ETL\ContextInterface;

class ObjectAlterationTransformer extends AlterationTransformer
{

    protected $transformerFunction;

    public function transform($object, ContextInterface $context)
    {
        if (!is_object) {
            throw new \Exception(sprintf('The argument need to an object, given "%s"', var_export($object, true)));
        }
        call_user_func($this->transformerFunction, $object);

        return $object;
    }

}
