<?php

namespace Earls\OxPeckerDataBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Earls\OxPeckerDataBundle\QueueManager\QueueDBHelper;

class OxPeckerQueueRepository extends EntityRepository
{
    public function getCommandsByNameDataTierPending($nameDataTier)
    {
        $commandsList = $this->findBy(array('dataTierType' => $nameDataTier, 'status' => QueueDBHelper::STATUS_PENDING));

        return $commandsList;
    }

    public function getCommandByNameDataTierRunning($nameDataTier)
    {
        $commandsList = $this->findBy(array('dataTierType' => $nameDataTier, 'status' => QueueDBHelper::STATUS_RUNNING));

        return $commandsList;
    }

    public function getCommandsForPurgeByDataTier($nameDataTier)
    {
        $commandsList = $this->findBy(array('dataTierType' => $nameDataTier, 'status' => array(
            QueueDBHelper::STATUS_DONE, QueueDBHelper::STATUS_CANCELLED)));

        return $commandsList;
    }

    public function getCommandByPID($pid)
    {
        $command = $this->findOneoBy('pid' => $pid);

        return $command;
    }

}