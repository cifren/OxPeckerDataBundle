<?php
namespace Earls\OxPeckerDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Earls\OxPeckerDataBundle\Entity\OxPeckerTask
 *
 * @ORM\Table(name="oxpecker_task")
 * @ORM\Entity(repositoryClass="Earls\OxPeckerDataBundle\Repository\OxPeckerTaskRepository")
 */

class OxPeckerTask
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
     * @var string $taskId
     *
     * @ORM\Column(name="task_id", type="string", length=32)
     */
    protected $taskId;

    /**
     * @var datetime $dataTierType
     *
     * @ORM\Column(name="datatier_type", type="string", nullable=true)
     */
    protected $dataTierType;

    /**
     * @var string $pid
     *
     * @ORM\Column(name="pid", type="string", length=32, nullable=true)
     */
    protected $pid;

    /**
     * @var datetime $startTime
     *
     * @ORM\Column(name="start_time", type="datetime", nullable=true)
     */
    protected $startTime;

    /**
     * @var integer $status
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    protected $status;

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
}