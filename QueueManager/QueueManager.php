<?php

namespace Earls\OxPeckerDataBundle\QueueManager;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Earls\OxPeckerDataBundle\QueueManager\QueueManagerInterface;

class QueueManager extends QueueDBHelper
{
    protected $input;
    protected $output;
    protected $container;
    protected $command;
    protected $force;
    protected $scriptPath;
    protected $environment;

    public function __construct(InputInterface $input, OutputInterface $output, $container, QueueManagerInterface $command)
    {
        $this->input = $input;
        $this->output = $output;
        $this->container = $container;
        $this->command = $command;
        $this->force = ($this->input->getOption('force')) ? TRUE : FALSE;
        $this->scriptPath = $this->container->getParameter('script_path'); 
        $this->environment = $this->container->get('kernel')->getEnvironment();
        parent::__construct($this->container->get('Doctrine'));
    }

    public function execute()
    {
        if($this->input->getOption('init')){
            $this->initProcess();
        }
        $nameDataTier = $this->input->getArgument('namedatatier');
        $taskId = $this->getRunningTask($nameDataTier);
        //$this->killTask($taskId);
        $this->pushCommandQueue($taskId, $nameDataTier, $this->input);
        $this->processCommand();        
    }

    protected function processCommand()
    {        
        //$this->command->executeCommand($this->input, $this->output);
        var_dump('Done');
    }

    protected function initProcess()
    {
        $this->createOxPeckerTables();
    }

    protected function getRunningTask($nameDataTier)
    {
        if(($taskId = $this->checkTaskRunning($nameDataTier)) == NULL){
            $taskId = $this->createNewTask($nameDataTier);
        }
        
        return $taskId;
    }
}

