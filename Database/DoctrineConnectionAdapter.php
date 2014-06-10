<?php

namespace Earls\OxPeckerDataBundle\Database;

//use Doctrine\ORM\Query\ResultSetMapping;
use Earls\OxPeckerDataBundle\Database\ConnectionAdapter;
use Doctrine\ORM\Query\ResultSetMapping;

include_once ('app/AppKernel.php');

class DoctrineConnectionAdapter extends ConnectionAdapter
{
    
    // change these values
//'mysql://point:jhj@nP@10.100.2.85/earls'
    protected $host = '10.100.2.85';

    protected $user = 'point';

    protected $pass = 'jhj@nP';

    protected $db = 'test';
    
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
        
        $this->createDoctrineConnection();
    }
    
    /**
     * query -  used as an adapter method to hide the possible different uses of the internal
     *          db connection's query method
     * 
     * @param   string  the query string
     * 
     * @return array|boolean|null depending on the query 
     */
    public  function query($queryString) {
        $result = null;
        
        $this->connection->beginTransaction();
        
        try{
            
           $stmt = $this->connection->getConnection()->prepare($queryString);
           $stmt->execute();
           $result = $stmt->fetchAll();
          
        }catch(\Exception $e){
            $result = $e->getMessage();
            $this->connection->rollback();
            return $result;
        }        
        $this->connection->commit();
        
        return $result;
    }
    
    /**
     * createDoctrineConnection     since doctrine likes to be instantiated from the kernel's container
     *                              we load->boot the kernel, and get the entity manager from the container.
     *                              From there we are actually referencing the EM as the connection object
     *                              but we have hidden that from the user in the adapter interface
     */
    private function createDoctrineConnection() {
        $kernel = new \AppKernel(
            isset($options['config']) ? $options['config'] : 'dev',
            isset($options['debug']) ? (boolean) $options['debug'] : true
            );       
        $kernel->boot(); 
          
        $this->connection = $kernel->getContainer()->get('doctrine')->getManager($this->db);    
        $this->connection->getConfiguration()->setSQLLogger(null);
                    
    }
}
