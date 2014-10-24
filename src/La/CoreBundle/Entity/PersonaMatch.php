<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PersonaMatch
 */
class PersonaMatch
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var float
     */
    private $difference;

    private $user;
    private $persona;

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
     * Set difference
     *
     * @param float $difference
     * @return PersonaMatch
     */
    public function setDifference($difference)
    {
        $this->difference = $difference;

        return $this;
    }

    /**
     * Get difference
     *
     * @return float
     */
    public function getDifference()
    {
        return $this->difference;
    }

    public function getMatch()
    {
        return (100 - $this->difference);
    }
    /**
     * Set user
     *
     * @param \La\CoreBundle\Entity\User $user
     * @return PersonaMatch
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
     * @return PersonaMatch
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
