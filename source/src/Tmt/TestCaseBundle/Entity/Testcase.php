<?php
namespace Tmt\TestCaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tmt\TestCaseBundle\Entity\Testcase
 *
 * @ORM\Table(name="tmt_testcase")
 * @ORM\Entity
 */
class Testcase
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $projectId;

    /**
     * @ORM\Column(type="text")
     */
    private $precondition;

    /**
     * @ORM\Column(type="integer")
     */
    private $preconditionId;

    /**
     * @ORM\Column(type="text")
     */
    private $postcondition;

    /**
     * @ORM\Column(type="integer")
     */
    private $postconditionId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $version;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastUser;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $lastState;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $lastError;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     */
    private $data;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set projectId
     *
     * @param integer $projectId
     * @return Testcase
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;
    
        return $this;
    }

    /**
     * Get projectId
     *
     * @return integer 
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * Set precondition
     *
     * @param string $precondition
     * @return Testcase
     */
    public function setPrecondition($precondition)
    {
        $this->precondition = $precondition;
    
        return $this;
    }

    /**
     * Get precondition
     *
     * @return string 
     */
    public function getPrecondition()
    {
        return $this->precondition;
    }

    /**
     * Set preconditionId
     *
     * @param integer $preconditionId
     * @return Testcase
     */
    public function setPreconditionId($preconditionId)
    {
        $this->preconditionId = $preconditionId;
    
        return $this;
    }

    /**
     * Get preconditionId
     *
     * @return integer 
     */
    public function getPreconditionId()
    {
        return $this->preconditionId;
    }

    /**
     * Set postcondition
     *
     * @param string $postcondition
     * @return Testcase
     */
    public function setPostcondition($postcondition)
    {
        $this->postcondition = $postcondition;
    
        return $this;
    }

    /**
     * Get postcondition
     *
     * @return string 
     */
    public function getPostcondition()
    {
        return $this->postcondition;
    }

    /**
     * Set postconditionId
     *
     * @param integer $postconditionId
     * @return Testcase
     */
    public function setPostconditionId($postconditionId)
    {
        $this->postconditionId = $postconditionId;
    
        return $this;
    }

    /**
     * Get postconditionId
     *
     * @return integer 
     */
    public function getPostconditionId()
    {
        return $this->postconditionId;
    }

    /**
     * Set version
     *
     * @param \DateTime $version
     * @return Testcase
     */
    public function setVersion($version)
    {
        $this->version = $version;
    
        return $this;
    }

    /**
     * Get version
     *
     * @return \DateTime 
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set lastUser
     *
     * @param string $lastUser
     * @return Testcase
     */
    public function setLastUser($lastUser)
    {
        $this->lastUser = $lastUser;
    
        return $this;
    }

    /**
     * Get lastUser
     *
     * @return string 
     */
    public function getLastUser()
    {
        return $this->lastUser;
    }

    /**
     * Set lastDate
     *
     * @param \DateTime $lastDate
     * @return Testcase
     */
    public function setLastDate($lastDate)
    {
        $this->lastDate = $lastDate;
    
        return $this;
    }

    /**
     * Get lastDate
     *
     * @return \DateTime 
     */
    public function getLastDate()
    {
        return $this->lastDate;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Testcase
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set lastState
     *
     * @param boolean $lastState
     * @return Testcase
     */
    public function setLastState($lastState)
    {
        $this->lastState = $lastState;
    
        return $this;
    }

    /**
     * Get lastState
     *
     * @return boolean 
     */
    public function getLastState()
    {
        return $this->lastState;
    }

    /**
     * Set lastError
     *
     * @param string $lastError
     * @return Testcase
     */
    public function setLastError($lastError)
    {
        $this->lastError = $lastError;
    
        return $this;
    }

    /**
     * Get lastError
     *
     * @return string 
     */
    public function getLastError()
    {
        return $this->lastError;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Testcase
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set data
     *
     * @param string $data
     * @return Testcase
     */
    public function setData($data)
    {
        $this->data = $data;
    
        return $this;
    }

    /**
     * Get data
     *
     * @return string 
     */
    public function getData()
    {
        return $this->data;
    }
}