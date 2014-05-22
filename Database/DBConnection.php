<?php

namespace Earls\OxPeckerDataBundle\Database;


class DBConnection
{
    // change these values
//'mysql://point:jhj@nP@10.100.2.85/earls'
    private $host = '10.100.2.85';

    private $user = 'point';

    private $pass = 'jhj@nP';

    private $db ='test';

    private $lastQuery='';

    var $stack;

    private $rows;

    public function __construct($host=null, $user=null, $pass=null, $db=null) {
        if(!is_null($host)) {
            $this->user = $user;
            $this->pass = $pass;
            $this->db = $db;
            $this->host = $host;
        }

    }

    public function setLogger(Logger $logger) {
        $this->logger = $logger;
    }
    
    function getAllRowsAsArray(){
        if(isset($this->stack))
            return $this->stack;

        $this->stack=array();
        while ($ra=mysqli_fetch_array($this->rows)) {
             array_push($this->stack,$ra);
        }

        unset($this->rows);

        return $this->stack;
    }

    public function setCustomer(SQLInterface $customer) {
        if(!($customer instanceof SQLInterface)) {
            throw new InterfaceNotImplementedException();
        }

        $this->user = $customer->dbUsername;
        $this->pass = $customer->dbPassword;
        $this->db = $customer->dbName;
        $this->host = $customer->dbHost;

    }

    public function beginTransaction(){
        $conn = $this->getConnection();
        mysqli_query("BEGIN");
    }

    public function commitTransaction(){
        $conn = $this->getConnection();
        mysqli_query("COMMIT");
    }

    public function rollbackTransaction(){
        $conn = $this->getConnection();
        mysqli_query("BEGIN");
    }

    public function getConnection(){
        $conn = mysqli_connect($this->host, $this->user, $this->pass, $this->db);

        if (!$conn) {
            die('Could not connect: ' . mysql_error());
        }
        //mysqli_select_db($this->db);

        return $conn;
    }

    public function query($query, $fetch = true){

        $this->lastQuery = $query;

        $conn = mysqli_connect($this->host, $this->user, $this->pass, $this->db);

        if (!$conn) {
            die('Could not connect: ' . mysqli_error());
        }

        //mysql_select_db($this->db);

        $results = mysqli_query($conn, utf8_decode($query));
        if (!$results) {
            die('Invalid query: ' . mysqli_error($conn));
        }
        if(strtolower(substr($query,0,6)) == 'delete' ) {
            return 0;
        }elseif(strtolower(substr($query,0,6)) == 'insert') {
            return mysqli_insert_id();
        }elseif(strtolower(substr($query,0,6) =='update')) {
            return;
        }

        //mysql_close($conn);
        if($fetch && $results){
            $stack=array();
            while ($ra=mysqli_fetch_array($results, MYSQL_ASSOC)) {

                 array_push($stack,$ra);
            }

            unset($results);

            return $stack;
        } elseif(!$results) {
            return;
        }

        return mysqli_insert_id();
    }

    public function getTableColumnMappings(AbstractEntity $entity){
        if(!$entity instanceof AbstractEntity){
            throw new \RuntimeException('DBConnection::getTableColumnMappings - entity my be instance of AbstractEntity');
        }
       // $columns = $this->query('SHOW COLUMNS FROM ' . $tableName);

        $mappings = new ColumnMappings($this);
        $columns = $mappings->getTableColumnList($entity->getTableName());
        return $columns;
    }

    public function getLastQuery(){
        return $this->lastQuery;
    }
}
