<?php

// src/Acme/DemoBundle/Command/GreetCommand.php
namespace Earls\OxPeckerDataBundle\Builders;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Earls\OxPeckerDataBundle\Builders\ReportBuilder;


class ConsoleReportBuilderDelete extends ConsoleReportBuilder
{
    protected function configure()
    {
        $this
            ->setName('reportbuilder:delete')
            ->setDescription('list a report')
            ->addArgument('reportType', InputArgument::REQUIRED, 'What type of report do you want to delete?')
            ->addArgument('parameters', InputArgument::IS_ARRAY, 'Please specify an array of parameters')
            ->addOption('connection', null, InputArgument::OPTIONAL, 'If not set, will use the default DBConnection class');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $builder = new ReportBuilder();
        
        $reportType = $input->getArgument('reportType');
        $params = $input->getArgument('parameters');
        $connection = $input->getOption('connection');
       
        if(isset($connection)) {
            $connection = null;
        }
        $result = $builder->handleRequest('delete', $params, $reportType, $connection);
        
        
        $output->writeln($result);
    }
}