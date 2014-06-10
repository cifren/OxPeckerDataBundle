<?php

namespace Earls\OxPeckerDataBundle\Database;


/**
 * ConnectionAdapter    an Adapter class (wrapper class) for the connection object so the user doesn't
 *                      care what they are dealing with
 * 
 * @author  Dave Meikle
 * @date    2014-05-21
 */
abstract class ConnectionAdapter
{
    protected $connection = null;
    
    
    
    public function __construct($connection) {
        $this->connection = $connection;      
    }
    
    /**
     * query - the method that hides the implementation from the user
     */
    public abstract function query($queryString);
}
