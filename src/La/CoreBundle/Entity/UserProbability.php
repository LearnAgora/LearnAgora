<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;


/**
 * @Serializer\ExclusionPolicy("all")
 *
 */
class UserProbability
{
    /**
     * @var integer
     *
     * @Serializer\Expose
     */
    private $id;
    /**
     * @var integer
     *
     * @Serializer\Expose
     */
    private $probability;
    /**
     * @var User
     */
    private $user;
    /**
     * @var LearningEntity
     */
    private $learningEntity;
    /**
     * @var Profile
     *
     * @Serializer\Expose
     */
    private $profile;


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
     * Set probability
     *
     * @param string $probability
     * @return UserProbability
     */
    public function setProbability($probability)
    {
        $this->probability = $probability;

        return $this;
    }

    /**
     * Get probability
     *
     * @return float
     */
    public function getProbability()
    {
        return $this->probability;
    }

    /**
     * Set user
     *
     * @param \La\CoreBundle\Entity\User $user
     * @return UserProbability
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
     * Set profile
     *
     * @param \La\CoreBundle\Entity\Profile $profile
     * @return UserProbability
     */
    public function setProfile(\La\CoreBundle\Entity\Profile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \La\CoreBundle\Entity\Profile 
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Set learningEntity
     *
     * @param \La\CoreBundle\Entity\LearningEntity $learningEntity
     * @return UserProbability
     */
    public function setLearningEntity(\La\CoreBundle\Entity\LearningEntity $learningEntity = null)
    {
        $this->learningEntity = $learningEntity;

        return $this;
    }

    /**
     * Get learningEntity
     *
     * @return \La\CoreBundle\Entity\LearningEntity 
     */
    public function getLearningEntity()
    {
        return $this->learningEntity;
    }
}
