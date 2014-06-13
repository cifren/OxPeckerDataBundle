<?php

namespace Earls\OxPeckerDataBundle\QueueManager;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Input\InputInterface;
use Earls\OxPeckerDataBundle\Entity\OxPeckerLog;
use Earls\OxPeckerDataBundle\Entity\OxPeckerTask;
use Earls\OxPeckerDataBundle\Entity\OxPeckerQueue;

class QueueDBHelper
{
    const OxPeckerLogEntity = 'Earls\OxPeckerDataBundle\Entity\OxPeckerLog';
    const OxPeckerTaskEntity = 'Earls\OxPeckerDataBundle\Entity\OxPeckerTask';
    const OxPeckerQueueEntity = 'Earls\OxPeckerDataBundle\Entity\OxPeckerQueue';

    const STATUS_WAITING = 0;
    const STATUS_PENDING = 1;
    const STATUS_RUNNING = 2;
    const STATUS_DONE = 3;
    const STATUS_PAUSED = 4;    
    const STATUS_STOPPED = 5;
    const STATUS_CANCELLED = 6;

    protected $doctrine;
    protected $entityManager;
    protected $connection;
    
    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
        if(($this->entityManager = $this->findDatabase()) == NULL){
            throw new \Exception('OxPecker entities classes are not defined');
        }
        $this->connection = $this->entityManager->getConnection();
    }

    //------------------------------- Initiation OxPecker -----------------------------------

    public function createOxPeckerTables()
    {        
        //---- Initialize OxPeckerLog ----
        $metaDataLog = $this->entityManager->getClassMetaData(self::OxPeckerLogEntity);
        $metaDataLog->setPrimaryTable(array('name' => $metaDataLog->getTableName()));
        //---- Initialize OxPeckerQueue ----
        $metaDataQueue = $this->entityManager->getClassMetaData(self::OxPeckerQueueEntity);
        $metaDataQueue->setPrimaryTable(array('name' => $metaDataQueue->getTableName()));
        //---- Initialize OxPeckerTask ----
        $metaDataTask = $this->entityManager->getClassMetaData(self::OxPeckerTaskEntity);
        $metaDataTask->setPrimaryTable(array('name' => $metaDataTask->getTableName()));

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema(array($metaDataLog, $metaDataQueue, $metaDataTask));

        if($this->environment === 'test'){
            $code = $schemaTool->getCreateSchemaSql(array($metaDataLog, $metaDataQueue, $metaDataTask));        
            $fileLocator = new FileLocator();
            $path = $fileLocator->locate('ImportQueueManagement.sql');
            if ($this->force === TRUE){
                if(!is_dir($dir = dirname($path))){
                    mkdir($dir, 0777, true);
                }
                file_put_contensts($path, $code);
            }
            else{
                print_r($code);            
            }
        }
    }

    //------------------------------- Task processes -----------------------------------

    public function checkTaskRunning($nameDataTier)
    {
        $tasksList = $this->entityManager->getRepository(self::OxPeckerTaskEntity)->getTaskByNameDataTier($nameDataTier);
        if(count($tasksList) == 0){

            return NULL;
        }
        else if(count($tasksList) > 1){
            foreach($tasksList as $task){
                $this->entityManager->remove($task);
            }
            $this->entityManager->flush();

            return NULL;
        }

        return $tasksList[0]->getTaskid();
    }

    public function createNewTask($nameDataTier)
    {
        if(empty($nameDataTier)){
            throw new \Exception('Cannot initiate a new task without DataTier');
        }

        $newTask = new OxPeckerTask();
        $taskId = $this->generateId();

        $newTask->setTaskId($taskId);
        $newTask->setDataTierType($nameDataTier);
        $newTask->setStartTime(new \DateTime("now"));
        $newTask->setStatus(self::STATUS_RUNNING);
        $newTask->setPid(NULL);
        
        $this->entityManager->persist($newTask);
        $this->entityManager->flush();

        return $taskId;
    }

    public function killTask($taskId)
    {
        $tasks = $this->entityManager->getRepository(self::OxPeckerTaskEntity)->getTaskByTaskId($taskId);
        if(!empty($tasks)){
            foreach($tasks as $task){
                $nameDataTier = $task->getDataTierType();
                $commandsList = $this->entityManager->getRepository(self::OxPeckerQueueEntity)->getCommandsByNameDataTierPending($nameDataTier);
                if(empty($commandsList)){
                    $this->entityManager->remove($task);
                }
            }
            $this->entityManager->flush();

            return TRUE;
        }

        return FALSE;
    }

    //------------------------------- Queue processes -----------------------------------

    public function pushCommandQueue($taskId, $nameDataTier, InputInterface $input)
    {
        $command = new OxPeckerQueue();
        $pid = $this->generateId();
        $command->setPid($pid);
        $command->setDataTierType($nameDataTier);
        $command->setQueueTime(new \DateTime('now'));
        $command->setCommandType($this->command->getCommandType());
        $command->setInputArguments($this->serialize($this->input));
        $command->setStatus(self::STATUS_PENDING);
        $command->setTaskId($taskId);
        $command->setStartTime(NULL);
        $command->setFinishTime(NULL);
        
        $this->entityManager->persist($command);
        $this->entityManager->flush();

        return $command;       
    }

    public function purgeCommandQueue($nameDataTier)
    {
        $commandsList = $this->entityManager->getRepository(self::OxPeckerQueueEntity)->getCommandsForPurgeByDataTierType($nameDataTier);
        foreach($commandsList as $command){
            $this->entityManager->remove($command);
        }
        $this->entityManager->flush();

        return TRUE;
    }

    public function changeCommandStatus($pid, $newStatus)
    {
        $command = $this->entityManager->getRepository(self::OxPeckerQueueEntity)->getCommandByPID($pid);
        if($command != NULL){
            $command->setStatus($newStatus);
            $this->entityManager->persist($command);
            $this->entityManager->flush();

            return $command;
        }

        return NULL;
    }

    public function getCommandRunning($nameDataTier)
    {
        $command = $this->entityManager->getRepository(self::OxPeckerQueueEntity)->getCommandByNameDataTierRunning($nameDataTier));
        if($command != NULL){
            $command->setStatus($newStatus);
            $this->entityManager->persist($command);
            $this->entityManager->flush();

            return $command;
        }

        return NULL;
    }
    //------------------------------- Commom processes -----------------------------------
    
    protected function findDatabase()
    {
        $databases = $this->doctrine->getConnection()->getSchemaManager()->listDatabases();
        foreach($databases as $database){            
            try{
                $em = $this->doctrine->getManager($database);
                foreach($em->getConfiguration()->getEntityNamespaces() as $namespace){
                    $oxPeckerTask = $namespace . '\OxPeckerTask';
                    $oxPeckerLog = $namespace . '\OxPeckerLog';
                    $oxPeckerQueue = $namespace . '\OxPeckerQueue';

                    if((class_exists($oxPeckerTask) === TRUE) && (class_exists($oxPeckerLog) === TRUE) && (class_exists($oxPeckerQueue) === TRUE)){
                        return $em;
                    }
                }
            }
            catch(\Exception $e){
                continue;
            }
        }
        
        return NULL;
    }

    protected function generateId()
    {
        return hash('md5', microtime());
    }

    protected function serialize($input)
    {
        $objectSerialized = serialize($input);

        return $objectSerialized;
    }

    protected function getInputObject($serializedObject)
    {
        return unserialize($serializedObject);
    }
}
