<?php
namespace Earls\OxPeckerDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * Earls\OxPeckerDataBundle\Entity\OxPeckerLog
 *
 * @ORM\Table(name="oxpecker_log", indexes={@Index(name="pid_idx", columns={"pid"})})
 * @ORM\Entity
 */

class OxPeckerLog 
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
     * @var integer $pId
     *
     * @ORM\Column(name="pid", type="string", length=32)
     */
    protected $pId;

    /**
     * @var datetime $logTime
     *
     * @ORM\Column(name="log_times", type="datetime", nullable=true)
     */
    protected $logTime;

    /**
     * @var text $logMessage
     *
     * @ORM\Column(name="log_message", type="text", nullable=true)
     */
    protected $logMessage;

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
     * Get logTime
     *
     * @return datetime $logTime
     */
    public function getLogTime()
    {
        return $this->logTime;
    }
    
    /**
     * Set logTime
     *
     * @param datetime $logTime
     */
    public function setLogTime($timestamp)
    {
        $this->logTime = $timestamp;
    }

    /**
     * Get logMessage
     *
     * @return text $logMessage
     */
    public function getLogMessage()
    {
        return $this->logMessage;
    }
    
    /**
     * Set logMessage
     *
     * @param text $logMessage
     */
    public function setLogMessage($logMessage)
    {
        $this->logMessage = $logMessage;
    }

}