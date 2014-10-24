<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Affinity
 */
class Affinity
{
    /**
     * @var integer
     */
    private $id;


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
     * @var string
     */
    private $value;

    /**
     * @var \La\CoreBundle\Entity\User
     */
    private $user;

    /**
     * @var \La\CoreBundle\Entity\Agora
     */
    private $agora;


    /**
     * Set value
     *
     * @param string $value
     * @return Affinity
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set user
     *
     * @param \La\CoreBundle\Entity\User $user
     * @return Affinity
     */
    public function setUser(\La\CoreBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \La\CoreBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set agora
     *
     * @param \La\CoreBundle\Entity\Agora $agora
     * @return Affinity
     */
    public function setAgora(\La\CoreBundle\Entity\Agora $agora = null)
    {
        $this->agora = $agora;

        return $this;
    }

    /**
     * Get agora
     *
     * @return \La\CoreBundle\Entity\Agora
     */
    public function getAgora()
    {
        return $this->agora;
    }
}
