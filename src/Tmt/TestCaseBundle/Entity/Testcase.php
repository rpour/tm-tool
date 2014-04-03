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
    private $json;

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
     * Gets the value of json.
     *
     * @return mixed
     */
    public function getJson()
    {
        return json_decode($this->json, 1);
    }
    
    /**
     * Sets the value of json.
     *
     * @param mixed $json the json
     *
     * @return self
     */
    public function setJson($json)
    {
        $this->json = json_encode($json);

        return $this;
    }
}