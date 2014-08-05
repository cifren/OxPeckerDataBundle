<?php

namespace Earls\OxPeckerDataBundle\DataSource;

use Earls\OxPeckerDataBundle\DataSource\ORMDataSource;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class DataSourceManager
{

    protected $entityManager;

    public function createTableFromDataSource(ORMDataSource $dataSource)
    {

        $entityName = $dataSource->getEntityName();

        $this->dropTable($entityName);
        $this->createTable($entityName);
        $this->insertTable($entityName, $this->getSql($dataSource->getQuery()), $dataSource->getMapping());
    }

    public function dropTable($entityName)
    {
        var_dump("dropTable $entityName");
        //very Slow
//        $tool = new SchemaTool($this->getEntityManager());
//        $classes = array(
//            $this->getEntityManager()->getClassMetadata($entityName),
//        );
//        $tool->dropSchema($classes);

        $classMetadata = $this->getEntityManager()->getClassMetadata($entityName);
        $sql = "DROP TABLE {$classMetadata->getTableName()}";

        try{
            $this->getEntityManager()->getConnection()->query($sql);
        } catch (\Exception $e) {
            
        }
    }

    protected function createTable($entityName)
    {
        var_dump("createTable $entityName");
        $tool = new SchemaTool($this->getEntityManager());
        $classes = array(
            $this->getEntityManager()->getClassMetadata($entityName),
        );
        $tool->createSchema($classes);
    }

    /**
     * 
     * @return type
     */
    public function insertTable($entityName, $query, $mapping)
    {
        var_dump("insertTable $entityName");
        $classMetadata = $this->getEntityManager()->getClassMetadata($entityName);

        $connection = $this->getEntityManager()->getConnection();

        $fieldsString = null;
        foreach ($mapping as $targetField) {
            $fieldsString .= $fieldsString ? ',' : '';

            $fieldsString .= $this->getColumnName($classMetadata, $targetField);
        }

        $sql = "INSERT INTO {$classMetadata->getTableName()} ($fieldsString) ({$query})";

        $connection->query($sql);
    }

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
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    public function getSql($query)
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

}
