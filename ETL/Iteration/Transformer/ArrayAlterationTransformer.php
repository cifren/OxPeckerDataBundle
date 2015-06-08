<?php

namespace Earls\OxPeckerDataBundle\ETL\Iteration\Transformer;

use Knp\ETL\ContextInterface;

class ArrayAlterationTransformer extends AlterationTransformer
{

    protected $transformerFunction;

    public function transform(array $array, ContextInterface $context)
    {
        $args = array_merge($array, $this->args);
        $arrayTransformed = call_user_func_array($this->transformerFunction, $args);

        return $arrayTransformed;
    }

}
