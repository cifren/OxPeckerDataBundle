<?php

namespace Earls\OxPeckerDataBundle\ETL\Iteration\Transformer;

use Knp\ETL\ContextInterface;

class ObjectAlterationTransformer extends AlterationTransformer
{

    protected $transformerFunction;

    public function transform(array $array, ContextInterface $context)
    {
        call_user_func($this->transformerFunction, $array);

        return $object;
    }

}
