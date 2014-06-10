<?php

namespace Earls\OxPeckerDataBundle\Database;

/**
 * DBConnection     a standard DB Connection class to be used if we choose not to have the overhead of a different connector
 * 
 * @author  Dave Meikle 
 * @date    2014-05-21
 */
class DBConnection
{
    
//'mysql://point:jhj@nP@10.100.2.85/earls'

    // change these values
    protected $host = '10.100.2.85';

    protected $user = 'point';

    protected $pass = 'jhj@nP';

    protected $db ='test'; //by default we will point to a test db if no concept name is provided

    private $lastQuery='';

    var $stack;

    private $rows;
    
    private $conn = null;

    /**
     * constructor
     * 
     * @param string    the connection string - a pipe delimited list of parameters, intended to be
     *                  simple enough to create from a command line
     * 
     * @tutorial        new DBConnection('10.100.2.85|earlsus|point|jhj@nP')
     */
    public function __construct($connectionString = null) {
        if(!is_null($connectionString) && strlen($connectionString) > 0) {
            list(
                $this->host,
                $this->db,
                $this->user,
                $this->pass            
            ) = explode('|', $connectionString);
        }

    }
    
    public function getHost() {
        return $this->host;
    }
    
    public function getDatabaseName() {
        return $this->db;
    }
    
    public function getUsername() {
        return $this->user;
    }
    
    public function getPassword() {
        return $this->pass;
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

   
    public function beginTransaction(){
      
        mysqli_query($this->getConnection(), "BEGIN");
    }

    public function commitTransaction(){
       
        mysqli_query($this->getConnection(), "COMMIT");
    }

    public function rollbackTransaction(){
       
        mysqli_query($this->getConnection(), "BEGIN");
    }

    public function getConnection(){
        if(is_null($this->conn)) {
            $this->conn = mysqli_connect('p:' . $this->host, $this->user, $this->pass, $this->db);

            if (!$this->conn) {
                die('Could not connect: ' . mysql_error());
            }
        }
        
        return $this->conn;
    }

    public function query($query, $fetch = true){

        $this->lastQuery = $query;
      

        $results = mysqli_query($this->getConnection(), utf8_decode($query));
        if (!$results) {
            die('Invalid query: ' . mysqli_error($this->getConnection()));
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


    public function getLastQuery(){
        return $this->lastQuery;
    }
}
