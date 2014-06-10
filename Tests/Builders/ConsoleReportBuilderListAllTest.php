<?php

namespace Earls\OxPeckerDataBundle\Tests\Builders;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;

use Earls\OxPeckerDataBundle\Builders\ConsoleReportBuilderList;

include_once ('app/AppKernel.php');

class ConsoleReportBuilderListAllTest extends \PHPUnit_Framework_TestCase
{
    protected $kernel = null;
    
    public function setUp()
    {
        
          $this->kernel = new \AppKernel(
            isset($options['config']) ? $options['config'] : 'dev',
            isset($options['debug']) ? (boolean) $options['debug'] : true
            );
       
        $this->kernel->boot();       
    }
    
    public function testExecute()
    {
        // mock the Kernel or create one depending on your needs
        $application = new Application($this->kernel);
        $application->add(new ConsoleReportBuilderList());

        $command = $application->find('reportbuilder:list');
        $commandTester = new CommandTester($command);
        $result = $commandTester->execute(
            array(
                'reportType'    => 'InventoryReport',
                'parameters'  => array("reportDate >= '2014-01-01'", "reportDate <= current_date()")
            )
        );

       print_r($result);

        // ...
    }
    
    
}