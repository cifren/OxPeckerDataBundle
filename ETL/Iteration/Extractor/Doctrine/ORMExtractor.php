<?php

namespace Earls\OxPeckerDataBundle\ETL\Iteration\Extractor\Doctrine;

use Knp\ETL\Extractor\Doctrine\ORMExtractor as BaseExtractor;
use Knp\ETL\ContextInterface;
use Earls\OxPeckerDataBundle\ETL\Iteration\LoggableInterface;
use Psr\Log\LoggerInterface;

/**
 * Earls\OxPeckerDataBundle\ETL\Extractor\Doctrine\ORMEXtractor
 */
class ORMExtractor extends BaseExtractor implements LoggableInterface
{

    protected $query;

    /**
     * Could be a Query or a QueryBuilder
     */
    public function __construct($query)
    {
        if (empty($query)) {
            throw new \Exception('Query can\'t be empty');
        }
        $this->query = $query;
    }

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

    public function getQuery()
    {
        if ($this->query instanceof \Doctrine\ORM\QueryBuilder) {
            return $this->query->getQuery();
        }

        return $this->query;
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
