<?php

namespace Earls\OxPeckerDataBundle\Database;

use Earls\OxPeckerDataBundle\Database\ConnectionAdapter;
use Monolog\Logger;


/**
 * ColumnMappings   retrieves a list of all columns in a table to determine whether
 *                  passed in parameters are applicable to the current table
 * 
 * @author  Dave Meikle
 * @date    2014-05-29
 */
class ColumnMappings
{
    private $connection = null;
    
    private $logger = null;
    
    private $tableColumns = array();
    
 
    
    public function __construct(ConnectionAdapter $connection, Logger $logger) {
        $this->connection = $connection;
        $this->logger = $logger;
    }
    
    /**
     * getColumnNames
     * 
     * @param string tablename -    the name of the table we want column list for. it will load them once
     *                              then keep them in an array to avoid multiple database queries
     * 
     * @return array                the column list
     */
    public function getColumnNames($tablename) {
        if(!array_key_exists($tablename, $this->tableColumns)) {
            $this->loadColumnNames($tablename);
        }
        
        return $this->tableColumns[$tablename];
    }
    
    /**
     * loadColumnNames
     * 
     * @param string tablename 
     */
    private function loadColumnNames($tablename) {
        
        $results = $this->connection->query('show columns from ' . $tablename);
        $columns = array();
        foreach($results as $row) {
            $columns[$row['Field']] =  $row['Field'];
        }
        
        $this->tableColumns[$tablename] = $columns;
        
    }
    
    
    /**
     * checkColumnExists
     * 
     * @param string tablename 
     * @param string column name
     * 
     * @return boolean
     */
    public function checkColumnExists($tablename, $column) {
        $tableColumns = $this->getColumnNames($tablename);        
        
        return array_key_exists($column, $tableColumns);
    }
    
}
