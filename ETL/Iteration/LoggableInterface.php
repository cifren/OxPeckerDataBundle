<?php

namespace Earls\OxPeckerDataBundle\ETL\Iteration;

use Psr\Log\LoggerInterface;

/**
 * Earls\OxPeckerDataBundle\ETL\Iteration\LoggableInterface.
 */
interface LoggableInterface
{
    public function setLogger(LoggerInterface $logger);

    public function getLogger();
}
