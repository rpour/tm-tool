<?php
namespace Tmt\TestCaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tmt\TestCaseBundle\Entity\Testcase
 *
 * @ORM\Table(name="tmt_testcase")
 * @ORM\Entity
 */
class Testcase {
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
     * @ORM\Column(type="string", length=255)
     */
    private $lasttest;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     */
    private $data;

    /**
     * Gets the value of id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the value of id.
     *
     * @param mixed $id the id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the value of projectId.
     *
     * @return mixed
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * Sets the value of projectId.
     *
     * @param mixed $projectId the project id
     *
     * @return self
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;

        return $this;
    }

    /**
     * Gets the value of precondition.
     *
     * @return mixed
     */
    public function getPrecondition()
    {
        return $this->precondition;
    }

    /**
     * Sets the value of precondition.
     *
     * @param mixed $precondition the precondition
     *
     * @return self
     */
    public function setPrecondition($precondition)
    {
        $this->precondition = $precondition;

        return $this;
    }

    /**
     * Gets the value of preconditionId.
     *
     * @return mixed
     */
    public function getPreconditionId()
    {
        return $this->preconditionId;
    }

    /**
     * Sets the value of preconditionId.
     *
     * @param mixed $preconditionId the precondition id
     *
     * @return self
     */
    public function setPreconditionId($preconditionId)
    {
        $this->preconditionId = $preconditionId;

        return $this;
    }

    /**
     * Gets the value of postcondition.
     *
     * @return mixed
     */
    public function getPostcondition()
    {
        return $this->postcondition;
    }

    /**
     * Sets the value of postcondition.
     *
     * @param mixed $postcondition the postcondition
     *
     * @return self
     */
    public function setPostcondition($postcondition)
    {
        $this->postcondition = $postcondition;

        return $this;
    }

    /**
     * Gets the value of postconditionId.
     *
     * @return mixed
     */
    public function getPostconditionId()
    {
        return $this->postconditionId;
    }

    /**
     * Sets the value of postconditionId.
     *
     * @param mixed $postconditionId the postcondition id
     *
     * @return self
     */
    public function setPostconditionId($postconditionId)
    {
        $this->postconditionId = $postconditionId;

        return $this;
    }

    /**
     * Gets the value of version.
     *
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Sets the value of version.
     *
     * @param mixed $version the version
     *
     * @return self
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Gets the value of lasttest.
     *
     * @return mixed
     */
    public function getLasttest()
    {
        return $this->lasttest;
    }

    /**
     * Sets the value of lasttest.
     *
     * @param mixed $lasttest the lasttest
     *
     * @return self
     */
    public function setLasttest($lasttest)
    {
        $this->lasttest = $lasttest;

        return $this;
    }

    /**
     * Gets the value of name.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the value of name.
     *
     * @param mixed $name the name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the value of description.
     *
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the value of description.
     *
     * @param mixed $description the description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets the value of data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets the value of data.
     *
     * @param mixed $data the data
     *
     * @return self
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}