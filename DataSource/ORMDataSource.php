<?php

namespace Earls\OxPeckerDataBundle\DataSource;



class ORMDataSource extends DataSource
{

    protected $entityName;
    protected $mapping;
    protected $query;
    protected $options = array(
        'dropOnInit' => true
    );

    /**
     * __construct
     * 
     * @param string $entityName
     * @param string|\Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder $query
     * @param array $mapping
     * @param array $options
     */
    public function __construct($entityName, $query, array $mapping, array $options = null)
    {
        $this->entityName = $entityName;
        $this->query = $query;
        $this->mapping = $mapping;
        if ($options) {
            $this->setOptions($options);
        }
    }

    /**
     * getEntityName
     * 
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * getMapping
     * 
     * @return array
     */
    public function getMapping()
    {
        return $this->mapping;
    }

    /**
     * getQuery
     * 
     * @return string|\Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * setEntityName
     * 
     * @param string $entityName
     * @return \Earls\OxPeckerDataBundle\DataSource\ORMDataSource
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
        return $this;
    }

    /**
     * setMapping
     * 
     * @param array $mapping
     * @return \Earls\OxPeckerDataBundle\DataSource\ORMDataSource
     */
    public function setMapping(array $mapping)
    {
        $this->mapping = $mapping;
        return $this;
    }

    /**
     * setQuery
     * 
     * @param string|\Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder $query
     * @return \Earls\OxPeckerDataBundle\DataSource\ORMDataSource
     */
    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * getOptions
     * 
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * setOptions
     * 
     * @param array $options
     * @return \Earls\OxPeckerDataBundle\DataSource\ORMDataSource
     */
    public function setOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

}