<?php

namespace Earls\OxPeckerDataBundle\Commands;


use Earls\OxPeckerDataBundle\Commands\BaseCommand;

/**
 * ListAllCommand   retrieves a list based on supplied parameters
 * 
 * @author  Dave Meikle
 * @date    2014-05-21
 */
class ListAllCommand extends BaseCommand
{
    
    /**
     * main entry point for the class
     * 
     * @param  array params
     * 
     * @return array
     */
    public function execute(array $params) {
        
        $query = $this->report->toSQLListAll($params);
          
        $result = $this->connection->query($query);
      
        return $this->report->parseResults($result);
    }
    
   
}