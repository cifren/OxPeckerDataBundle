<?php

namespace Earls\OxPeckerDataBundle\ETL\Core;

use Knp\ETL\ContextInterface;

interface ETLProcessInterface
{

    public function process();

    public function getContext();

    public function setContext(ContextInterface $context);

    public function getLogger();

    public function setLogger($logger);
}
