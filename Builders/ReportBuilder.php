<?php

namespace Earls\OxPeckerDataBundle\Builders;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Earls\OxPeckerDataBundle\Exceptions\CommandNotFoundException;
use Earls\OxPeckerDataBundle\Command\DeleteCommand;
use Earls\OxPeckerDataBundle\Command\ListAllCommand;
use Earls\OxPeckerDataBundle\Command\ImportCommand;

use Earls\OxPeckerDataBundle\Database\DBConnection;
use Earls\OxPeckerDataBundle\Database\ConnectionAdapter;
use Earls\OxPeckerDataBundle\Database\StandardDBConnectionAdapter;
use Earls\OxPeckerDataBundle\Database\DoctrineConnectionAdapter;


use Pp3\DataTierBundle\Reports\BaseReport;
use Pp3\DataTierBundle\Reports\InventoryReport;
use Pp3\DataTierBundle\Reports\IngredientUsageReport;

use Doctrine\ORM\EntityManager;

/**
 * class ReportBuilder  this is the 'game console' - the main executable for the ReportBuilder module.
 *                      All requests are sent to the ReportBuilder and handled accordingly. No other
 *                      objects should be accessed in this module, they are all inner classes.
 * 
 * @author  Dave Meikle
 * @date    2014-05-21
 */
class ReportBuilder
{
    private $logger = null;
    
    const DELETE_ACTION = 'delete';
    const IMPORT_ACTION = 'import';
    const LIST_ACTION = 'list';
    
    private $connectionAdapter = null;
    
    private $connection = null;
    
    public function __construct() {
        $this->initLogger();    
    }
    
    /**
     * getConnectionAdapter
     * 
     * @param Object    the database connection to use
     */
    private function getConnectionAdapter() {
        
        if(is_null($this->connectionAdapter)) {
            $this->connectionAdapter = new DoctrineConnectionAdapter($this->connection);
        }         

        return $this->connectionAdapter;
    }
    
    private function setConnection($conn) {
        $this->connection = $conn;
    }
    
    /**
     * getReport    instantiates the report object
     * 
     * @param string reportType
     * 
     * @return BaseReport
     */
    private function getReport($reportType) {
        $report = null;
        if('InventoryReport' == $reportType) {
            return new InventoryReport($this->getConnectionAdapter(),$this->logger);
        }elseif('IngredientUsage' == $reportType) {
            return new IngredientUsageReport($this->getConnectionAdapter(),$this->logger);
        }
          
        $this->logger->addError("ReportBuilder::getReport unable to determine valid reportType $reportType");
          
        throw new \Exception("ReportBuilder::getReport unable to instantiate $reportType");
    }
    
    /**
     * initLogger   initializes the logger
     */
    protected function initLogger() {
        $this->logger = new Logger('phpUnitTest');
        $this->logger->pushHandler(new StreamHandler("app/logs/phpunit.log", Logger::DEBUG));  
        $this->logger->addDebug('ReportBuilder::initLogger completed successfully');     
    }
    
    /**
     * handleRequest    the entry point
     *                  handles all requests and processes them accordingly
     * 
     * @param string action         'import' ...'delete'... etc.
     * @param array  params         the parameters to use
     * @param string reportType     the type of report to run
     * @param string connString     the connection string parameters, pipe delimited. if not specified, default conn params will be used
     *                              typical connstring format:  '10.100.2.85|earls|point|jhj@nP' - the only thing that really needs to change is the
     *                              db name and the IP since this is a dev IP.
     * @param string connection     the name of database connection to use - a placeholder in case we decide to let doctrine run our big SQL statements
     */
    public function handleRequest($action, array $params, $reportType, $connection = null) {
       //set the connection and we'll figure out our adapter once we need it
        $this->setConnection($connection);
       
       
        $this->logger->addInfo('ReportBuilder::handleRequest requesting Command object');
        $cmd = $this->getCommand(
                                    $action, 
                                    $this->getConnectionAdapter(), 
                                    $this->getReport($reportType)
                                 );
        
        $this->logger->addInfo('ReportBuilder::handleRequest ' . get_class($cmd) . ' instantiated');
        $result = $cmd->execute($params);
        
        unset($cmd);
        
        $this->logger->addInfo('ReportBuilder::handleRequest completed');
        
        return $result;
    }
    
    /**
     * getCommand   determines the command to instantiate
     * 
     * @param string requestAction  the user's request for action
     * @param ConnectionAdapter connection
     * @param BaseReport report
     * 
     * @return BaseCommand
     * 
     * @throws CommandNotFoundException
     */
    private function getCommand($requestAction, ConnectionAdapter $connection, BaseReport $report) {
        
        if(self::DELETE_ACTION == $requestAction) {
            return new DeleteCommand($connection, $report, $this->logger);
        } elseif(self::IMPORT_ACTION == $requestAction) {
            return new ImportCommand($connection, $report, $this->logger);
        } elseif(self::LIST_ACTION == $requestAction) {
            return new ListAllCommand($connection, $report, $this->logger);
        }
        
        $this->addError('ReportBuilder::GetCommand unable to locate command for ' . $requestAction);
        
        throw new CommandNotFoundException('ReportBuilder::GetCommand unable to locate command for ' . $requestAction);
    }
}
