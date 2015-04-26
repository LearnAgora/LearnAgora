<?php

namespace La\CoreBundle\Event;

use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class UserProbabilityUpdatedEvent extends Event
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var LearningEntity
     */
    private $learningEntity;
    /**
     * Constructor.
     *
     * @param User $user
     * @param LearningEntity $learningEntity
     */
    public function __construct(User $user, LearningEntity $learningEntity)
    {
        $this->user = $user;
        $this->learningEntity = $learningEntity;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return LearningEntity
     */
    public function getLearningEntity()
    {
        return $this->learningEntity;
    }
}
