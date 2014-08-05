<?php

namespace Earls\OxPeckerDataBundle\ETL\Loader\Doctrine;

use Psr\Log\LoggerAwareTrait;
use Doctrine\ORM\EntityManager;
use Knp\ETL\ContextInterface;
use Knp\ETL\LoaderInterface;

class ORMLoader implements LoaderInterface
{

    use LoggerAwareTrait;

    private $counter = 0;
    private $flushEvery;
    private $entityManager;
    private $entityClass;

    public function __construct(EntityManager $entityManager, $flushEvery = 100)
    {
        $this->entityManager = $entityManager;
        $this->flushEvery = $flushEvery;
    }

    public function load($entity, ContextInterface $context)
    {
        if (null === $this->entityClass) {
            $this->entityClass = get_class($entity);
        }

        $this->entityManager->persist($entity);

        $this->counter++;

        if ($this->counter % $this->flushEvery === 0) {
            $this->flush($context);
        }

        if ($this->counter % $this->flushEvery === 0) {
            $this->clear($context);
        }
    }

    public function flush(ContextInterface $context)
    {
        echo "flush\n";
        $this->entityManager->flush();
        if (null !== $this->logger) {
            $this->logger->debug(sprintf('flush after %d persist hits', $this->counter));
        }
    }

    public function clear(ContextInterface $context)
    {
        $this->entityManager->clear($this->entityClass);
        if (null !== $this->logger) {
            $this->logger->debug(sprintf('clear after %d persist hits', $this->counter));
        }
    }

}
