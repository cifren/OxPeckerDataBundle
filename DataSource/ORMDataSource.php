<?php

namespace Earls\OxPeckerDataBundle\DataSource;

class ORMDataSource extends DataSource
{

    protected $entityName;
    protected $mapping;
    protected $query;
    
    public function __construct($entityName, $query, array $mapping)
    {
        $this->entityName = $entityName;
        $this->query = $query;
        $this->mapping = $mapping;
    }

    public function getEntityName()
    {
        return $this->entityName;
    }

    public function getMapping()
    {
        return $this->mapping;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
        return $this;
    }

    public function setMapping($mapping)
    {
        $this->mapping = $mapping;
        return $this;
    }

    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

}
