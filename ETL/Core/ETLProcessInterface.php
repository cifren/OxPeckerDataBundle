<?php

namespace Earls\OxPeckerDataBundle\ETL\Core;

use Knp\ETL\ContextInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

interface ETLProcessInterface
{

    public function process();

    public function getContext();

    public function setContext(ContextInterface $context);

    public function getLogger();

    public function setLogger(LoggerInterface $logger);
}
