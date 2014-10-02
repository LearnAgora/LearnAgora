<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trace
 */
class Trace
{
    /**
     * @var integer
     */
    private $id;

    private $createdTime;


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
     * @var \La\CoreBundle\Entity\Outcome
     */
    private $outcome;


    /**
     * Set user
     *
     * @param \La\CoreBundle\Entity\User $user
     * @return Trace
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
     * Set outcome
     *
     * @param \La\CoreBundle\Entity\Outcome $outcome
     * @return Trace
     */
    public function setOutcome(\La\CoreBundle\Entity\Outcome $outcome = null)
    {
        $this->outcome = $outcome;

        return $this;
    }

    /**
     * Get outcome
     *
     * @return \La\CoreBundle\Entity\Outcome 
     */
    public function getOutcome()
    {
        return $this->outcome;
    }



    /**
     * Set createdTime
     *
     * @param \DateTime $createdTime
     * @return Trace
     */
    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;

        return $this;
    }

    /**
     * Get createdTime
     *
     * @return \DateTime 
     */
    public function getCreatedTime()
    {
        return $this->createdTime;
    }
}
