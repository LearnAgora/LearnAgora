<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Goal
 */
class Goal
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
     * @var \La\CoreBundle\Entity\User
     */
    private $user;

    /**
     * @var \La\CoreBundle\Entity\Persona
     */
    private $persona;


    /**
     * Set user
     *
     * @param \La\CoreBundle\Entity\User $user
     * @return Goal
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
     * Set persona
     *
     * @param \La\CoreBundle\Entity\Persona $persona
     * @return Goal
     */
    public function setPersona(\La\CoreBundle\Entity\Persona $persona = null)
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * Get persona
     *
     * @return \La\CoreBundle\Entity\Persona 
     */
    public function getPersona()
    {
        return $this->persona;
    }
}
