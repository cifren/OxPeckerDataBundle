<?php

namespace Earls\OxPeckerDataBundle\Database;

abstract class ConnectionAdapter
{
    protected $connection = null;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    abstract public function query($queryString);
}
