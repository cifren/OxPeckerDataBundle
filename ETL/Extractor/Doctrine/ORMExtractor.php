<?php

namespace Earls\OxPeckerDataBundle\ETL\Extractor\Doctrine;

use Knp\ETL\Extractor\Doctrine\ORMExtractor as BaseExtractor;
use Knp\ETL\ContextInterface;

/**
 * Earls\OxPeckerDataBundle\ETL\Extractor\Doctrine\ORMEXtractor
 */
class ORMExtractor extends BaseExtractor
{

    /**
     * Seems to have a bug in Knp Bundle, correctif here
     * 
     * @param \Knp\ETL\ContextInterface $context
     * @return type
     */
    public function extract(ContextInterface $context)
    {
        $current = $this->next();

        return $current[0];
    }

}
