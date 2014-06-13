<?php
namespace Earls\OxPeckerDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Earls\OxPeckerDataBundle\Entity\OxPeckerQueue
 *
 * @ORM\Table(name="oxpecker_queue")
 * @ORM\Entity(repositoryClass="Earls\OxPeckerDataBundle\Repository\OxPeckerQueueRepository")
 */

class OxPeckerQueue
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */ 
    protected $id;

    /**
     * @var datetime $pid
     *
     * @ORM\Column(name="pid", type="string", nullable=true)
     */
    protected $pid;

    /**
     * @var datetime $dataTierType
     *
     * @ORM\Column(name="datatier_type", type="string", nullable=true)
     */
    protected $dataTierType;

    /**
     * @var datetime $queueTime
     *
     * @ORM\Column(name="queue_time", type="datetime", nullable=true)
     */
    protected $queueTime;

    /**
     * @var string $commandType
     *
     * @ORM\Column(name="command_type", type="string", length=255)
     */
    protected $commandType;

    /**
     * @var text $inputArguments
     *
     * @ORM\Column(name="input_arguments", type="text", nullable=true)
     */
    protected $inputArguments;

    /**
     * @var integer $status
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    protected $status;

    /**
     * @var string $taskId
     *
     * @ORM\Column(name="task_id", type="string", length=32)
     */
    protected $taskId;

    /**
     * @var datetime $startTime
     *
     * @ORM\Column(name="start_time", type="datetime", nullable=true)
     */
    protected $startTime;

    /**
     * @var datetime $finishTime
     *
     * @ORM\Column(name="finish_time", type="datetime", nullable=true)
     */
    protected $finishTime;

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get pid
     *
     * @return string $pid
     */
    public function getPid()
    {
        return $this->pid;
    }
    
    /**
     * Set pid
     *
     * @param string $pid
     */
    public function setPid($pid)
    {
        $this->pid = $pid;
    }

    /**
     * Get dataTierType
     *
     * @return string $dataTierType
     */
    public function getDataTierType()
    {
        return $this->dataTierType;
    }
    
    /**
     * Set dataTierType
     *
     * @param string $dataTierType
     */
    public function setDataTierType($dataTierType)
    {
        $this->dataTierType = $dataTierType;
    }

    /**
     * Get queueTime
     *
     * @return datetime $queueTime
     */
    public function getQueueTime()
    {
        return $this->queueTime;
    }
    
    /**
     * Set queueTime
     *
     * @param datetime $queueTime
     */
    public function setQueueTime($timestamp)
    {
        $this->queueTime = $timestamp;
    }

    /**
     * Get commandType
     *
     * @return string $commandType
     */
    public function getCommandType()
    {
        return $this->commandType;
    }
    
    /**
     * Set commandType
     *
     * @param string $commandType
     */
    public function setCommandType($commandType)
    {
        $this->commandType = $commandType;
    }

    /**
     * Get inputArguments
     *
     * @return string $inputArguments
     */
    public function getInputArguments()
    {
        return $this->inputArguments;
    }
    
    /**
     * Set inputArguments
     *
     * @param string $inputArguments
     */
    public function setInputArguments($inputArguments)
    {
        $this->inputArguments = $inputArguments;
    }

    /**
     * Get status
     *
     * @return integer $status
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * Set status
     *
     * @param integer $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get taskId
     *
     * @return string $taskId
     */
    public function getTaskId()
    {
        return $this->taskId;
    }
    
    /**
     * Set taskId
     *
     * @param string $taskId
     */
    public function setTaskId($taskId)
    {
        $this->taskId = $taskId;
    }

    /**
     * Get startTime
     *
     * @return datetime $startTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }
    
    /**
     * Set startTime
     *
     * @param datetime $startTime
     */
    public function setStartTime($timestamp)
    {
        $this->startTime = $timestamp;
    }

    /**
     * Get finishTime
     *
     * @return datetime $finishTime
     */
    public function getFinishTime()
    {
        return $this->finishTime;
    }
    
    /**
     * Set finishTime
     *
     * @param datetime $finishTime
     */
    public function setFinishTime($timestamp)
    {
        $this->finishTime = $timestamp;
    }
}