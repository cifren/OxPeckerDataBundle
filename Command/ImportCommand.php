<?php

namespace Earls\OxPeckerDataBundle\Command;


use Earls\OxPeckerDataBundle\Command\BaseCommand;
use Earls\OxPeckerDataBundle\Exceptions\HandlerNotImplementedException;

/**
 * ImportCommand   retrieves a list based on supplied parameters
 * 
 * @author  Dave Meikle
 * @date    2014-05-29
 */
class ImportCommand extends BaseCommand
{
    
    /**
     * main entry point for the class
     * 
     * @param  array params
     * 
     * @return array
     */
    public function execute(array $params) {
        
        //import process is complex so we are relying on custom handlers associated with the report to do the work for us
        if($this->report->hasHandler()) {
            
        
            //instantiate the handler - it needs the connection to determine column mappings
            $handler = $this->report->getHandler($this->connection, $this->logger);
            $handler->execute($params);
            
            unset($handler);
        }else {
            throw new HandlerNotImplementedException(get_class($this->report) . ' does not have an associated Handler written for it yet');
        }
    }
    
}
