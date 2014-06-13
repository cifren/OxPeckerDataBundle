<?php

namespace Earls\OxPeckerDataBundle\QueueManager;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Earls\OxPeckerDataBundle\QueueManager\QueueManager;

class CommandQueue extends ContainerAwareCommand
{
    protected function configure()
    {
        parent::configure();
        $this->addOption('init', NULL, InputOption::VALUE_NONE, 'Initialization Process')
        $this->addOption('force', NULL, InputOption::VALUE_NONE, 'Force to make changes directly to DB');        
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $queueManager = new QueueManager($input, $output, $this->getContainer(), $this);
        
        $queueManager->execute();
    }
}

