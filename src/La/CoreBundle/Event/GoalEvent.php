<?php

namespace La\CoreBundle\Event;

use La\CoreBundle\Entity\Goal;
use Symfony\Component\EventDispatcher\Event;

class GoalEvent extends Event
{
    /**
     * @var Goal
     */
    private $goal;

    /**
     * Constructor.
     *
     * @param Goal $goal
     */
    public function __construct(Goal $goal)
    {
        $this->goal = $goal;
    }

    /**
     * @return Goal
     */
    public function getGoal()
    {
        return $this->goal;
    }
}
