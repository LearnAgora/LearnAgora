<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;


/**
 * @Serializer\ExclusionPolicy("all")
 *
 */
class UserProbabilityEvent
{
    /**
     * @var integer
     *
     * @Serializer\Expose
     */
    private $id;
    /**
     * @var string
     *
     * @Serializer\Expose
     */
    private $message;
    /**
     * @var UserProbability
     *
     * @Serializer\Expose
     */
    private $userProbability;

    /**
     * @var integer
     *
     * @Serializer\Expose
     */
    private $threshold;

    /**
     * @var boolean
     *
     * @Serializer\Expose
     */
    private $seen = false;

    /**
     * @var boolean
     *
     * @Serializer\Expose
     */
    private $removed = false;

    /**
     * @Serializer\Expose
     */
    private $createdOn;

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
     * Set message
     *
     * @param string $message
     * @return UserProbabilityEvent
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set userProbability
     *
     * @param UserProbability $userProbability
     * @return UserProbabilityEvent
     */
    public function setUserProbability(UserProbability $userProbability = null)
    {
        $this->userProbability = $userProbability;

        return $this;
    }

    /**
     * Get userProbability
     *
     * @return UserProbability
     */
    public function getUserProbability()
    {
        return $this->userProbability;
    }

    /**
     * Set threshold
     *
     * @param integer $threshold
     * @return UserProbabilityEvent
     */
    public function setThreshold($threshold)
    {
        $this->threshold = $threshold;

        return $this;
    }

    /**
     * Get threshold
     *
     * @return integer 
     */
    public function getThreshold()
    {
        return $this->threshold;
    }

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     * @return UserProbabilityEvent
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * Get createdOn
     *
     * @return \DateTime 
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Set seen
     *
     * @param boolean $seen
     * @return UserProbabilityEvent
     */
    public function setSeen($seen)
    {
        $this->seen = $seen;

        return $this;
    }

    /**
     * Get seen
     *
     * @return boolean 
     */
    public function getSeen()
    {
        return $this->seen;
    }

    /**
     * Set removed
     *
     * @param boolean $removed
     * @return UserProbabilityEvent
     */
    public function setRemoved($removed)
    {
        $this->removed = $removed;

        return $this;
    }

    /**
     * Get removed
     *
     * @return boolean 
     */
    public function getRemoved()
    {
        return $this->removed;
    }
}
