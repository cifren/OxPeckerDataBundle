<?php

namespace Earls\OxPeckerDataBundle\Database;

abstract class ConnectionAdapter
{
    protected $connection = null;
    
    public function __construct($connection) {
        $this->connection = $connection;      
    }
    
    public abstract function query($queryString);
}
