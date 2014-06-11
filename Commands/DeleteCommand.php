<?php

namespace Earls\OxPeckerDataBundle\Commands;


use Earls\OxPeckerDataBundle\Commands\BaseCommand;

class DeleteCommand extends BaseCommand
{
    public function execute(array $params) {
        
        $query = $this->report->toSQLDelete($params);
        
        $this->connection->query($query);
        
        //no other work to perform
        return true;
    }
    
}