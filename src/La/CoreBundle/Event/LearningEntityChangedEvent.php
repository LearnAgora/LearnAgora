<?php

namespace La\CoreBundle\Event;

use La\CoreBundle\Entity\LearningEntity;
use Symfony\Component\EventDispatcher\Event;

class LearningEntityChangedEvent extends Event
{
    /**
     * @var LearningEntity
     */
    private $learningEntity;
    /**
     * Constructor.
     *
     * @param LearningEntity $learningEntity
     */
    public function __construct(LearningEntity $learningEntity)
    {
        $this->learningEntity = $learningEntity;
    }

    /**
     * @return LearningEntity
     */
    public function getLearningEntity() {
        return $this->learningEntity;
    }


}
