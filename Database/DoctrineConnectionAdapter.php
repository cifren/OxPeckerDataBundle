<?php

namespace Earls\OxPeckerDataBundle\Database;

//use Doctrine\ORM\Query\ResultSetMapping;
use Earls\OxPeckerDataBundle\Database\ConnectionAdapter;
use Doctrine\ORM\Query\ResultSetMapping;
use Pp3\DataTierBundle\Configuration\ReportConfiguration;
use Doctrine\ORM\EntityManager;

include_once ('app/AppKernel.php');

class DoctrineConnectionAdapter extends ConnectionAdapter
{
    
    /**
     * constructor
     * 
     * @param string    the connection string - a pipe delimited list of parameters, intended to be
     *                  simple enough to create from a command line
     * 
     * @tutorial        new DBConnection('14.100.3.44|dbname|username|password')
     */
     public function __construct(EntityManager $em) {
         $this->connection = $em;
         
         $this->connection->getConfiguration()->setSQLLogger(null);
     }
     
     /**
     * constructor
     * 
     * @param string    the connection string - a pipe delimited list of parameters, intended to be
     *                  simple enough to create from a command line
     * 
     * @tutorial        new DBConnection('14.100.3.44|dbname|username|password')
     */
    // public function __construct($connectionString = null) {
        // if(!is_null($connectionString) && strlen($connectionString) > 0) {
            // list(
                // $this->host,
                // $this->db,
                // $this->user,
                // $this->pass            
            // ) = explode('|', $connectionString);
        // }
//         
        // $this->createDoctrineConnection();
    // }
    
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
           $command = strtolower(substr($queryString,0,6));
           
           if( $command == 'update' || $command == 'insert' || $command == 'delete') {            
               $result = true;
           } else {
               $result = $stmt->fetchAll();
           }         
            
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
