<?php

namespace Earls\OxPeckerDataBundle\ETL\Core;

use Earls\OxPeckerDataBundle\ETL\SQL\DataSource\ORMDataSource;
use Knp\ETL\ContextInterface;

class SqlETLProcess implements ETLProcessInterface
{

    protected $context;
    protected $datasource;
    protected $logger;
    protected $datasourceManager;

    public function __construct($query, $entityName, array $mapping, array $options = null)
    {
        $this->datasource = new ORMDataSource($query, $entityName, $mapping, $options);
    }

    public function process()
    {
        $this->getDatasourceManager()->createTableFromDataSource($this->datasource);
    }

    public function getContext()
    {
        return $this->context;
    }

    public function setContext(ContextInterface $context)
    {
        $this->context = $context;
        return $this;
    }

    public function getLogger()
    {
        if (!$this->logger) {
            throw new \Exception('did you forget to setLogger ?');
        }

        return $this->logger;
    }

    public function setLogger($logger)
    {
        $this->logger = $logger;

        return $this;
    }

    public function getDatasourceManager()
    {
        return $this->datasourceManager;
    }

    public function setDatasourceManager($datasourceManager)
    {
        $this->datasourceManager = $datasourceManager;
        return $this;
    }

}
