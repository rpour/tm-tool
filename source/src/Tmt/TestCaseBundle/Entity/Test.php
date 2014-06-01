<?php
namespace Tmt\TestCaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tmt\TestCaseBundle\Entity\Test
 *
 * @ORM\Table(name="tmt_test")
 * @ORM\Entity
 */
class Test
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
    private $testcaseId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     */
    private $data;

    /**
     * @ORM\Column(type="boolean")
     */
    private $passed;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $userAgent;

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
     * Gets the value of testcaseId.
     *
     * @return mixed
     */
    public function getTestcaseId()
    {
        return $this->testcaseId;
    }

    /**
     * Sets the value of testcaseId.
     *
     * @param mixed $testcaseId the testcase id
     *
     * @return self
     */
    public function setTestcaseId($testcaseId)
    {
        $this->testcaseId = $testcaseId;

        return $this;
    }

    /**
     * Gets the value of date.
     *
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets the value of date.
     *
     * @param mixed $date the date
     *
     * @return self
     */
    public function setDate($date)
    {
        $this->date = $date;

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

    /**
     * Gets the value of passed.
     *
     * @return mixed
     */
    public function getPassed()
    {
        return $this->passed;
    }

    /**
     * Sets the value of passed.
     *
     * @param mixed $passed the passed
     *
     * @return self
     */
    public function setPassed($passed)
    {
        $this->passed = $passed;

        return $this;
    }

    /**
     * Gets the value of username.
     *
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Sets the value of username.
     *
     * @param mixed $username the username
     *
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Gets the value of userAgent.
     *
     * @return mixed
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Sets the value of userAgent.
     *
     * @param mixed $userAgent the user agent
     *
     * @return self
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }
}
