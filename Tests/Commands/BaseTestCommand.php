<?php

namespace Earls\OxPeckerDataBundle\Tests\Commands;

use Earls\OxPeckerDataBundle\Database\DBConnection;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


include_once ('app/AppKernel.php');

class BaseTestCommand extends \PHPUnit_Framework_TestCase
{
    private $logger = null;
    
    protected function getConnection($connstring = null) {
             
        return new DBConnection($connstring);
    }
    
    /**
     * createDoctrineConnection     since doctrine likes to be instantiated from the kernel's container
     *                              we load->boot the kernel, and get the entity manager from the container.
     *                              From there we are actually referencing the EM as the connection object
     *                              but we have hidden that from the user in the adapter interface
     */
    protected function createDoctrineConnection($dbName='earls') {
        $kernel = new \AppKernel('dev',true);       
        $kernel->boot(); 
       
        $connection = $kernel->getContainer()->get('doctrine')->getManager($dbName);    
        $connection->getConfiguration()->setSQLLogger(null);
        
        return $connection;
                    
    }
    protected function getLogger() {
        if(is_null($this->logger)) {
            $this->logger = new Logger('phpUnitTest');
            $this->logger->pushHandler(new StreamHandler("app/logs/phpunit.log", Logger::DEBUG));
        }
        
        return $this->logger;
    }
} 
