<?php
namespace Tmt\TestCaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tmt\TestCaseBundle\Entity\UserAgent
 *
 * @ORM\Table(name="tmt_useragent")
 * @ORM\Entity
 */
class UserAgent
{
    /**
     * @ORM\Column(type="string", length=32)
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $json;

    /**
     * Set id
     *
     * @param string $id
     * @return UserAgent
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set json
     *
     * @param string $json
     * @return UserAgent
     */
    public function setJson($json)
    {
        $this->json = $json;

        return $this;
    }

    /**
     * Get json
     *
     * @return string
     */
    public function getJson()
    {
        return $this->json;
    }
}
