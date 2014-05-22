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

use Pp3\DataTierBundle\Reports\BaseReport;
use Pp3\DataTierBundle\Reports\InventoryReport;


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
    
    public function __construct() {
        $this->initLogger();    
    }
    
    private function getConnectionAdapter($conn = null) {
        if(is_null($conn)) {
            return new StandardDBConnectionAdapter(new DBConnection());
        }    
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
            return new InventoryReport($this->logger);
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
     * @param string action
     * @param array  params     the parameters to use
     * @param string reportType the type of report to run
     * @param connection        the database connection to use
     */
    public function handleRequest($action, array $params, $reportType, $connection = null) {
        $this->addInfo('ReportBuilder::handleRequest requesting Command object');
        $cmd = $this->getCommand(
                                    $requestAction, 
                                    $this->getConnectionAdapter($connection), 
                                    $this->getReport($reportType)
                                 );
        
        $this->addInfo('ReportBuilder::handleRequest ' . get_class($cmd) . ' instantiated');
        $result = $cmd->execute($params);
        
        unset($cmd);
        
        $this->addInfo('ReportBuilder::handleRequest completed');
        
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
