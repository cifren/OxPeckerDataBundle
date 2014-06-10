<?php

namespace Earls\OxPeckerDataBundle\Database;

use Earls\OxPeckerDataBundle\Database\ConnectionAdapter;


/**
 * StandardDBConnectionAdapter
 * 
 * this class implements the DBConnection class and is intended to be used
 * as a 'default' if no other db connection object is specified
 * 
 * @author Dave Meikle
 * @data 2014-05-22
 */
class StandardDBConnectionAdapter extends ConnectionAdapter
{
    /**
     * query -  used as an adapter method to hide the possible different uses of the internal
     *          db connection's query method
     * 
     * @param   string  the query string
     * 
     * @return array|boolean|null depending on the query 
     */
    public  function query($queryString) {
        $result = null;
        
        $this->connection->beginTransaction();
        
        try{
            $result = $this->connection->query($queryString);
        }catch(\Exception $e){
            $result = $e->getMessage();
            $this->connection->rollbackTransaction();
            return $result;
        }        
        $this->connection->commitTransaction();
        
        return $result;
    }
     
}
