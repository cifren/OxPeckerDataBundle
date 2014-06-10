<?php

// src/Acme/DemoBundle/Command/GreetCommand.php
namespace Earls\OxPeckerDataBundle\Builders;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Earls\OxPeckerDataBunder\Builders\ReportBuilder;


class ConsoleReportBuilder extends ContainerAwareCommand
{
    
    protected $args;
    


    protected function parseArguments($args){
        unset($args[0]); //remove the path to script variable
        $string = implode('&',$args);
        parse_str($string, $params);
        $this->args = $params;
    }

}