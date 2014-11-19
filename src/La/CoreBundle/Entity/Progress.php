<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Progress
 */
class Progress
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $value;
    /**
     * @var \La\CoreBundle\Entity\User
     */
    private $user;

    /**
     * @var \La\CoreBundle\Entity\LearningEntity
     */
    private $learningEntity;


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
     * Set value
     *
     * @param string $value
     * @return Progress
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
     * @return Progress
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
     * Set learningEntity
     *
     * @param \La\CoreBundle\Entity\LearningEntity $learningEntity
     * @return Progress
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
