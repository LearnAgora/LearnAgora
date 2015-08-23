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
}
