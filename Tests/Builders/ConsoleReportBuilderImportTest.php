<?php

namespace Earls\OxPeckerDataBundle\Tests\Builders;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;

use Earls\OxPeckerDataBundle\Builders\ConsoleReportBuilderImport;

include_once ('app/AppKernel.php');

class ConsoleReportBuilderImportTest extends \PHPUnit_Framework_TestCase
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
        $application->add(new ConsoleReportBuilderImport());

        $command = $application->find('reportbuilder:import');
        $commandTester = new CommandTester($command);
        $result = null;
        try{
           $result = $commandTester->execute(
                array(
                    'reportType'    => 'InventoryReport',
                    'parameters'  => array('reportDate >= 2014-01-01', 'reportDate <= current_date()'),
                )
            ); 
            $this->fail("This test should fail with no Handler present");
        }catch(\Exception $e){
            $this->assertEquals(615, $e->getCode());
        }
        

       echo "result is $result";

        // ...
    }
    
    
}