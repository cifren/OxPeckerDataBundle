<?php

namespace Earls\OxPeckerDataBundle\Command;


use Earls\OxPeckerDataBundle\Command\BaseCommand;

class DeleteCommand extends BaseCommand
{
    public function execute(array $params) {
        
        $query = $this->report->toSQLDelete($params);
            
        $this->connection->query($query);
        
        //no other work to perform
    }
    
}
