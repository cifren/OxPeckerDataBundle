<?php

namespace Earls\OxPeckerDataBundle\Repository;

use Doctrine\ORM\EntityRepository;

class OxPeckerTaskRepository extends EntityRepository
{
    public function getTaskByNameDataTier($nameDataTier)
    {
        $tasksList = $this->findBy(array('dataTierType' => $nameDataTier));

        return $tasksList;
    }

    public function getTaskByTaskId($taskId)
    {
        $task = $this->findBy(array('taskId' => $taskId));
        
        return $task;
    }
}