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
     * @param EntityManager    
     * 
     */
     public function __construct(EntityManager $em) {
         $this->connection = $em;
         
         $this->connection->getConfiguration()->setSQLLogger(null);
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
           $command = strtolower(substr($queryString,0,6));
           
           if( $command == 'update' || $command == 'insert' || $command == 'delete') {            
               $result = true;
           } else {
               $result = $stmt->fetchAll();
           }         
            
        }catch(\Exception $e){
            $result = array($e->getMessage());
            $this->connection->rollback();
           
            return $result;
        }        
        $this->connection->commit();
        
        return $result;
    }
    
    public function getDBName() {
        
        return $this->connection->getConnection()->getDatabase();
    }
}
