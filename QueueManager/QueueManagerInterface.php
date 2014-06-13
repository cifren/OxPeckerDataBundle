<?php

namespace Earls\OxPeckerDataBundle\QueueManager;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface QueueManagerInterface
{
    public function executeCommand(InputInterface $input, OutputInterface $output);

    public function getCommandType();
}
