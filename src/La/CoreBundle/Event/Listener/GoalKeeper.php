<?php

namespace La\CoreBundle\Event\Listener;

use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Event\GoalEvent;
use La\CoreBundle\Event\PersonaMatchEvent;
use La\CoreBundle\Events;
use La\CoreBundle\Model\Goal\GoalManager;

/**
 * @DI\Service
 */
class GoalKeeper
{
    /**
     * @var GoalManager
     */
    private $goalManager;

    /**
     * Constructor.
     *
     * @param GoalManager $goalManager
     *
     * @DI\InjectParams({
     *  "goalManager" = @DI\Inject("la_core.goal_manager")
     * })
     */
    public function __construct(GoalManager $goalManager)
    {
        $this->goalManager = $goalManager;
    }

    /**
     * @DI\Observe(Events::USER_GOAL_UPDATE)
     *
     * @param GoalEvent $event
     */
    public function onGoalUpdate(GoalEvent $event)
    {
        $this->goalManager->setGoal($event->getGoal());
    }

    /**
     * @DI\Observe(Events::USER_PERSONA_MATCH_UPDATE)
     *
     * @param PersonaMatchEvent $event
     */
    public function onPersonaMatchUpdate(PersonaMatchEvent $event)
    {
        $this->goalManager->updateGoal();
    }
}
