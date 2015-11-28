<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;


/**
 * @Serializer\ExclusionPolicy("all")
 *
 */
class UserProbabilityEvent extends Event
{
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


}
