<?php

namespace Earls\OxPeckerDataBundle\DataSource;

use Earls\OxPeckerDataBundle\DataSource\ORMDataSource;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Monolog\Logger;
use Doctrine\ORM\EntityManager;

class DataSourceManager
{

    protected $entityManager;
    protected $logger;

    /**
     * createTableFromDataSource
     * 
     * @param \Earls\OxPeckerDataBundle\DataSource\ORMDataSource $dataSource
     */
    public function createTableFromDataSource(ORMDataSource $dataSource)
    {
        $entityName = $dataSource->getEntityName();

        $this->getLogger()->notice(" - $entityName");
        $options = $dataSource->getOptions();

        if ($options['dropOnInit']) {
            $this->dropTable($entityName);
        }
        $this->createTable($entityName);
        $this->insertTable($entityName, $dataSource->getQuery(), $dataSource->getMapping());
    }

    /**
     * dropTable
     * 
     * @param string $entityName
     */
    public function dropTable($entityName)
    {
        $this->getLogger()->notice("Drop Table $entityName");
        //very Slow
//        $tool = new SchemaTool($this->getEntityManager());
//        $classes = array(
//            $this->getEntityManager()->getClassMetadata($entityName),
//        );
//        $tool->dropSchema($classes);

        $classMetadata = $this->getEntityManager()->getClassMetadata($entityName);
        $sql = "DROP TABLE {$classMetadata->getTableName()}";

        try {
            $this->getEntityManager()->getConnection()->query($sql);
        } catch (\Exception $e) {
            $this->getLogger()->debug("Catch Exception {$e->getMessage()}");
        }
    }

    /**
     * createTable
     * 
     * @param string $entityName
     */
    public function createTable($entityName)
    {
        $this->getLogger()->notice("Create Table $entityName");
        $tool = new SchemaTool($this->getEntityManager());
        $classes = array(
            $this->getEntityManager()->getClassMetadata($entityName),
        );

        try {
            $tool->createSchema($classes);
        } catch (\Exception $e) {
            $this->getLogger()->debug("Catch Exception {$e->getMessage()}");
        }
    }

    /**
     * insertTable
     * 
     * @param string $entityName
     * @param string|Query|QueryBuilder $query
     * @param array $mapping
     */
    public function insertTable($entityName, $query, array $mapping)
    {
        $this->getLogger()->notice("Insert Table $entityName");
        $classMetadata = $this->getEntityManager()->getClassMetadata($entityName);

        $connection = $this->getEntityManager()->getConnection();

        $fieldsString = null;
        foreach ($mapping as $targetField) {
            $fieldsString .= $fieldsString ? ',' : '';

            $fieldsString .= $this->getColumnName($classMetadata, $targetField);
        }

        $sqlSelect = $this->getSql($query);

        $sql = "INSERT INTO {$classMetadata->getTableName()} ($fieldsString) ({$sqlSelect})";

        $connection->query($sql);
    }

    /**
     * Get Column Name from the table giving targetfield from the entity
     * 
     * @param \Doctrine\ORM\Mapping\ClassMetadata $classMetadata
     * @param string $targetField
     * 
     * @return string
     */
    public function getColumnName(ClassMetadata $classMetadata, $targetField)
    {
        if (isset($classMetadata->columnNames[$targetField])) {
            $columnName = $classMetadata->getFieldMapping($targetField)['columnName'];
        } else {
            $columnName = $classMetadata->getAssociationMapping($targetField)['joinColumns'][0]['name'];
        }

        return $columnName;
    }

    /**
     * getEntityManager
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * 
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @return \Earls\OxPeckerDataBundle\DataSource\DataSourceManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * getSql
     * 
     * @param string|Query|QueryBuilder $query
     * @return string
     */
    protected function getSql($query)
    {
        if ($query instanceof QueryBuilder) {
            $query = $query->getQuery();
        }

        if ($query instanceof Query) {
            $sql = $query->getSql();
        } else {
            $sql = $query;
        }

        return $sql;
    }

    /**
     * getLogger
     * 
     * @return \Symfony\Bridge\Monolog\Logger
     * @throws \Exception
     */
    public function getLogger()
    {
        if (!$this->logger) {
            throw new \Exception('did you forget to setLogger ?');
        }

        return $this->logger;
    }

    /**
     * setLogger
     * 
     * @param \Symfony\Bridge\Monolog\Logger $logger
     * @return \Earls\OxPeckerDataBundle\DataSource\DataSourceManager
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;

        return $this;
    }

}
