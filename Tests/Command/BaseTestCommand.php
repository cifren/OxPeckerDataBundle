<?php

namespace Earls\OxPeckerDataBundle\Tests\Command;

use Earls\OxPeckerDataBundle\Database\DBConnection;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class BaseTestCommand extends \PHPUnit_Framework_TestCase
{
    private $logger = null;
    
    protected function getConnection() {
               
        return new DBConnection();
    }
    
    protected function getLogger() {
        if(is_null($this->logger)) {
            $this->logger = new Logger('phpUnitTest');
            $this->logger->pushHandler(new StreamHandler("../../../../apps/logs/phpunit.log", Logger::DEBUG));
        }
        
        return $this->logger;
    }
} 
